<?php
/**
 * WordPress eXtended RSS file parser implementations
 *
 * @package WordPress
 * @subpackage Importer
 */

/**
 * WordPress Importer class for managing parsing of WXR files.
 */
class Auxels_Import_Parser {
  function parse( $file ) {
    // Attempt to use proper XML parsers first
    if ( extension_loaded( 'simplexml' ) ) {
      $parser = new AUXELS_WXR_Parser_SimpleXML;
      $result = $parser->parse( $file );

      // If SimpleXML succeeds or this is an invalid WXR file then return the results
      if ( ! is_wp_error( $result ) || 'SimpleXML_parse_error' != $result->get_error_code() )
        return $result;
    } elseif ( extension_loaded( 'xml' ) ) {
      $parser = new AUXELS_WXR_Parser_XML;
      $result = $parser->parse( $file );

      // If XMLParser succeeds or this is an invalid WXR file then return the results
      if ( ! is_wp_error( $result ) || 'XML_parse_error' != $result->get_error_code() )
        return $result;
    }

    // We have a malformed XML file, so display the error and fallthrough to regex
    if ( isset($result) && defined('IMPORT_DEBUG') && IMPORT_DEBUG ) {
      echo '<pre>';
      if ( 'SimpleXML_parse_error' == $result->get_error_code() ) {
        foreach  ( $result->get_error_data() as $error )
          echo $error->line . ':' . $error->column . ' ' . esc_html( $error->message ) . "\n";
      } elseif ( 'XML_parse_error' == $result->get_error_code() ) {
        $error = $result->get_error_data();
        echo $error[0] . ':' . $error[1] . ' ' . esc_html( $error[2] );
      }
      echo '</pre>';
      echo '<p><strong>' . __( 'There was an error when reading this WXR file', 'wordpress-importer' ) . '</strong><br />';
      echo __( 'Details are shown above. The importer will now try again with a different parser...', 'wordpress-importer' ) . '</p>';
    }

    // use regular expressions if nothing else available or this is bad XML
    $parser = new AUXELS_WXR_Parser_Regex;
    return $parser->parse( $file );
  }
}

/**
 * WXR Parser that makes use of the SimpleXML PHP extension.
 */
class AUXELS_WXR_Parser_SimpleXML {

    function parse( $file ) {

        $options = $option = array();

        $internal_errors = libxml_use_internal_errors(true);

        $dom = new DOMDocument;
        $old_value = null;
        if ( function_exists( 'libxml_disable_entity_loader' ) ) {
          $old_value = libxml_disable_entity_loader( true );
        }
        $success = $dom->loadXML( file_get_contents( $file ) );
        if ( ! is_null( $old_value ) ) {
          libxml_disable_entity_loader( $old_value );
        }

        if ( ! $success || isset( $dom->doctype ) ) {
          return new WP_Error( 'SimpleXML_parse_error', __( 'There was an error when reading this WXR file', 'wordpress-importer' ), libxml_get_errors() );
        }

        $xml = simplexml_import_dom( $dom );
        unset( $dom );

        // halt if loading produces an error
        if ( ! $xml )
          return new WP_Error( 'SimpleXML_parse_error', __( 'There was an error when reading this WXR file', 'wordpress-importer' ), libxml_get_errors() );

        $wxr_version = $xml->xpath('/rss/channel/wp:wxr_version');
        if ( ! $wxr_version )
          return new WP_Error( 'AUXELS_WXR_parse_error', __( 'This does not appear to be a WXR file, missing/invalid WXR version number', 'wordpress-importer' ) );

        $wxr_version = (string) trim( $wxr_version[0] );
        // confirm that we are dealing with the correct file format
        if ( ! preg_match( '/^\d+\.\d+$/', $wxr_version ) )
          return new WP_Error( 'AUXELS_WXR_parse_error', __( 'This does not appear to be a WXR file, missing/invalid WXR version number', 'wordpress-importer' ) );

        $base_url = $xml->xpath('/rss/channel/wp:base_site_url');
        $base_url = (string) trim( $base_url[0] );

        $namespaces = $xml->getDocNamespaces();
        if ( ! isset( $namespaces['wp'] ) )
          $namespaces['wp'] = 'http://wordpress.org/export/1.1/';
        if ( ! isset( $namespaces['excerpt'] ) )
          $namespaces['excerpt'] = 'http://wordpress.org/export/1.1/excerpt/';

        $wp = $xml->channel->children( $namespaces['wp'] );
        // grab cats, tags and terms

        foreach ( $wp->option as $option ) {
            $options[ $option->option_key[0]->__toString() ] = $option->option_value[0]->__toString();
        }

        return $options;
    }

}

/**
 * WXR Parser that makes use of the XML Parser PHP extension.
 */
class AUXELS_WXR_Parser_XML {

    var $wp_tags = array(
        'wp:option'
    );
    var $wp_sub_tags = array(
        'wp:option_name', 'wp:option_value'
    );

    function parse( $file ) {

        $this->wxr_version = $this->in_post = $this->cdata = $this->data = $this->sub_data = $this->in_tag = $this->in_sub_tag = false;
        $this->authors = $this->posts = $this->term = $this->category = $this->tag = array();

        $xml = xml_parser_create( 'UTF-8' );
        xml_parser_set_option( $xml, XML_OPTION_SKIP_WHITE, 1 );
        xml_parser_set_option( $xml, XML_OPTION_CASE_FOLDING, 0 );
        xml_set_object( $xml, $this );
        xml_set_character_data_handler( $xml, 'cdata' );
        xml_set_element_handler( $xml, 'tag_open', 'tag_close' );

        if ( ! xml_parse( $xml, file_get_contents( $file ), true ) ) {
          $current_line = xml_get_current_line_number( $xml );
          $current_column = xml_get_current_column_number( $xml );
          $error_code = xml_get_error_code( $xml );
          $error_string = xml_error_string( $error_code );
          return new WP_Error( 'XML_parse_error', 'There was an error when reading this WXR file', array( $current_line, $current_column, $error_string ) );
        }
        xml_parser_free( $xml );

        if ( ! preg_match( '/^\d+\.\d+$/', $this->wxr_version ) )
          return new WP_Error( 'AUXELS_WXR_parse_error', __( 'This does not appear to be a WXR file, missing/invalid WXR version number', 'wordpress-importer' ) );

        return array(
          'authors' => $this->authors,
          'posts' => $this->posts,
          'categories' => $this->category,
          'tags' => $this->tag,
          'terms' => $this->term,
          'base_url' => $this->base_url,
          'version' => $this->wxr_version
        );
    }

    function tag_open( $parse, $tag, $attr ) {
        if ( in_array( $tag, $this->wp_tags ) ) {
          $this->in_tag = substr( $tag, 3 );
          return;
        }

        if ( in_array( $tag, $this->wp_sub_tags ) ) {
          $this->in_sub_tag = substr( $tag, 3 );
          return;
        }

        switch ( $tag ) {
          case 'category':
            if ( isset($attr['domain'], $attr['nicename']) ) {
              $this->sub_data['domain'] = $attr['domain'];
              $this->sub_data['slug'] = $attr['nicename'];
            }
            break;
          case 'item': $this->in_post = true;
          case 'title': if ( $this->in_post ) $this->in_tag = 'post_title'; break;
          case 'guid': $this->in_tag = 'guid'; break;
          case 'dc:creator': $this->in_tag = 'post_author'; break;
          case 'content:encoded': $this->in_tag = 'post_content'; break;
          case 'excerpt:encoded': $this->in_tag = 'post_excerpt'; break;

          case 'wp:term_slug': $this->in_tag = 'slug'; break;
          case 'wp:meta_key': $this->in_sub_tag = 'key'; break;
          case 'wp:meta_value': $this->in_sub_tag = 'value'; break;
        }
    }

    function cdata( $parser, $cdata ) {
        if ( ! trim( $cdata ) )
          return;

        if ( false !== $this->in_tag || false !== $this->in_sub_tag ) {
          $this->cdata .= $cdata;
        } else {
          $this->cdata .= trim( $cdata );
        }
    }

    function tag_close( $parser, $tag ) {
        switch ( $tag ) {
          case 'wp:option':
            $n = substr( $tag, 3 );
            array_push( $this->$n, $this->data );
            $this->data = false;
            break;
        }

        $this->cdata = false;
    }
}

/**
 * WXR Parser that uses regular expressions. Fallback for installs without an XML parser.
 */
class AUXELS_WXR_Parser_Regex {

    var $options = array();

    function __construct() {
        $this->has_gzip = is_callable( 'gzopen' );
    }

    function parse( $file ) {
        $wxr_version = $in_post = false;

        $fp = $this->fopen( $file, 'r' );
        if ( $fp ) {
          while ( ! $this->feof( $fp ) ) {
            $importline = rtrim( $this->fgets( $fp ) );

            if ( false !== strpos( $importline, '<wp:option>' ) ) {
              preg_match( '|<wp:option>(.*?)</wp:option>|is', $importline, $option );
              $this->options[] = $this->process_option( $option[1] );
              continue;
            }
            if ( $in_post ) {
              $post .= $importline . "\n";
            }
          }

          $this->fclose($fp);
        }

        if ( ! $wxr_version )
          return new WP_Error( 'AUXELS_WXR_parse_error', __( 'This does not appear to be a WXR file, missing/invalid WXR version number', 'wordpress-importer' ) );

        return array(
          'options' => $this->options
        );
    }

    function get_tag( $string, $tag ) {
        preg_match( "|<$tag.*?>(.*?)</$tag>|is", $string, $return );
        if ( isset( $return[1] ) ) {
              if ( substr( $return[1], 0, 9 ) == '<![CDATA[' ) {
                if ( strpos( $return[1], ']]]]><![CDATA[>' ) !== false ) {
                  preg_match_all( '|<!\[CDATA\[(.*?)\]\]>|s', $return[1], $matches );
                  $return = '';
                  foreach( $matches[1] as $match )
                    $return .= $match;
                } else {
                  $return = preg_replace( '|^<!\[CDATA\[(.*)\]\]>$|s', '$1', $return[1] );
                }
              } else {
                $return = $return[1];
              }
        } else {
          $return = '';
        }
        return $return;
    }

    function process_option( $t ) {
        return array(
          'option_name'  => $this->get_tag( $t, 'wp:option_name' ),
          'option_value' => $this->get_tag( $t, 'wp:option_value' )
        );
    }

    function _normalize_tag( $matches ) {
        return '<' . strtolower( $matches[1] );
    }

    function fopen( $filename, $mode = 'r' ) {
        if ( $this->has_gzip )
            return gzopen( $filename, $mode );
        return fopen( $filename, $mode );
    }

    function feof( $fp ) {
        if ( $this->has_gzip )
            return gzeof( $fp );
        return feof( $fp );
    }

    function fgets( $fp, $len = 8192 ) {
        if ( $this->has_gzip )
            return gzgets( $fp, $len );
        return fgets( $fp, $len );
    }

    function fclose( $fp ) {
        if ( $this->has_gzip )
            return gzclose( $fp );
        return fclose( $fp );
    }

}
