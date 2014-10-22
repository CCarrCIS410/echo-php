<?php
/**
 * Defines class E_Tag and related functions
 *
 * @author Matt Beall
 */

/**
 * Tag class
 *
 * Connects to database and creates tag object.
 *
 * @author Matt Beall
 * @since 0.0.1
 */
class E_Tag {

  /**
   * @var int $tag_id_PK The ID of the tag
   */
  public $tag_id_PK;

  /**
   * @var string $tag_name The name of the tag
   */
  public $tag_name = '';

  /**
   * @var string $tag_color The text color of the tag
   */
  public $tag_color = 'ffffff';

  /**
   * @var string $tag_bg The background color of the tag
   */
  public $tag_bg = '777777';

  /**
   * @var int $tag_visible If 0, tag has been "deleted"; else, tag is visible.
   */
  public $tag_visible = 1;

  /**
   * Construct E_Tag object
   *
   * Takes PDO and constructs E_Tag class
   *
   * @since 0.0.1
   *
   * @param  object $tags The PHP Data Object
   */
  public function __construct( $tags ) {
    foreach ( $tags as $tag ) {
      get_class($tag);
      foreach ( $tag as $key => $value )
        $this->$key = $value;
    }
  }

  /**
   * Execute query
   *
   * Attempt to connect to database and execute SQL query
   * If successful, return results.
   *
   * @since 0.0.1
   *
   * @uses edb::connect()
   * @throws PDOException if connection or query cannot execute
   *
   * @param  string $query The SQL query to be executed
   * @return object        Data retrieved from database
   * @var    string $conn  The PHP Data Object
   */
  public static function query( $query ) {
    global $edb;
    $conn = $edb->connect();
    try {
      $query = $conn->query($query);
      do {
        if ($query->columnCount() > 0) {
            $results = $query->fetchAll(PDO::FETCH_OBJ);
        }
      }
      while ($query->nextRowset());

      $conn = null;

      return $results;
    }
    catch (PDOException $e) {
      $conn = null;
      die ('Query failed: ' . $e->getMessage());
    }
  }

  /**
   * Get tag information from database
   *
   * Prepare and execute query to select tag from database
   *
   * @since 0.0.1
   *
   * @uses self::query()
   *
   * @param  int    $tag_id The primary key of the tag being retrieved from the database
   * @return object         Data retrieved from database
   * @var    string $conn   The PHP Data Object for the connection
   */
  public static function get_instance( $tag_id ) {
    global $edb;

    $tag_id = (int) $tag_id;

    if ( ! $tag_id )
      return false;

    $_tag = self::query("SELECT TOP 1 * FROM tags WHERE tag_id_PK = $tag_id");

    return new E_Tag ( $_tag );
  }

  /**
   * Insert tag in database
   *
   * Prepare and execute query to create tag in tags table
   *
   * @since 0.0.1
   *
   * @uses edb::insert()
   * @uses _hexadec()
   *
   * @param string $tag_name  The name of the tag
   * @param string $tag_color The text color of the tag
   * @param string $tag_bg    The background color of the tag
   *
   * @return void
   *
   * @var int $tag_id The primary key of the tag being registered, as created in tag database
   *
   * @todo Test
   */
  public static function set_instance( $tag_name, $tag_color = null, $tag_bg = null ) {
    global $edb;

    $tag_color = !empty($tag_color) ? _hexadec($tag_color) : 'ffffff';
    $tag_bg    = !empty($tag_bg)    ? _hexadec($tag_bg)    : '777777';

    $tag_visible = 1;

    $edb->insert('tags', 'tag_name,tag_color,tag_bg,tag_visible', "'$tag_name', '#$tag_color', '#$tag_bg', $tag_visible" );
  }
}

/**
 * Get the E_Tag class
 *
 * @since 0.0.1
 *
 * @uses E_Tag::get_instance() Constructs E_Tag class and gets class object
 *
 * @param  int    $tag_id The ID of the tag to get
 * @return object $tag The E_Tag class with the tag's data
 */
function get_tag( $tag_id ) {
  $tag_id = (int) $tag_id;
  $tag = E_Tag::get_instance( $tag_id );
  return $tag;
}

/**
 * Get specific data from a tag object
 *
 * @since 0.0.1
 *
 * @param  object $tag The E_Tag class containing the data for a tag
 * @param  string $key  The name of the field to be retrieved
 * @return mixed        The value of the data retreived
 */
function get_tag_data( $tag, $key ) {
  if (!empty($tag))
    return $tag->$key;
  else
    echo 'ERROR: There is no data in the tag object.';
    die;
}

/**
 * Get the name of the tag
 *
 * @since 0.0.1
 *
 * @uses get_tag_data()
 *
 * @param  object $tag      The E_Tag class containing the data for the tag
 * @return string           The name of the tag
 * @var    string $tag_name The name of the tag
 */
function get_tag_name( $tag ) {
  $tag_name = get_tag_data( $tag , 'tag_name' );
  return $tag_name;
}

/**
 * Get the text color of the tag
 *
 * @since 0.0.1
 *
 * @uses get_tag_data()
 *
 * @param  object $tag       The E_Tag class containing the data for the tag
 * @return string            The text color of the tag
 * @var    string $tag_color The text color of the tag
 */
function get_tag_color( $tag ) {
  $tag_color = get_tag_data( $tag , 'tag_color' );
  return $tag_color;
}

/**
 * Get the background color of the tag
 *
 * @since 0.0.1
 *
 * @uses get_tag_data()
 *
 * @param  object $tag    The E_Tag class containing the data for the tag
 * @return string         The background color of the tag
 * @var    string $tag_bg The background color of the tag
 */
function get_tag_bg( $tag ) {
  $tag_bg = get_tag_data( $tag , 'tag_bg' );
  return $tag_bg;
}

/**
 * Check if the tag is visible or not
 *
 * @since 0.0.1
 *
 * @uses get_tag_data()
 *
 * @param  object $tag         The E_Tag class containing the data for the tag
 * @return bool
 * @var    string $tag_visible If 0, tag has been "deleted"; else, tag is visible.
 */
function is_tag_visible( $tag ) {
  $tag_visible = get_tag_data( $tag , 'tag_visible' );
  $tag_visible = (int) $tag_visible;

  if ($tag_visible == 1)
    return true;
  else
    return false;
}

function _hexadec( $string ) {
  $str = preg_replace( '[^0-9a-f]', '', strtolower($string) );
  $len = strlen($str);

  if ($len == 6 | $len == 3)
    return $str;
  elseif ($len > 6)
    return substr($str,0,6);
}
