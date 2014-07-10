<?php
/*
    Plugin Name: Codescar Radio Widget
    Plugin URI: http://codescar.eu/radio_widget.zip
    Description: Radio widget uses html5 audio element to play radio in sidebar
    Author: Sudavar
    Version: 0.1
    Author URI: http://profiles.wordpress.org/sudavar
    Contributors: lion2486
    Tags: radio widget, codescar, radio, radio stations, radio player, audio element html5, widget
    Requires at least: 3.0.1
    Tested up to: 3.9
    Text Domain: codescar-radio-widget
    License: GPLv2
    License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


/*
 * Adds Radio_widget widget.
 */
class Codescar_Radio_widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        $radio = array(
            'default' => 'http://diesi.live24.gr/diesi1013',
            'volume' => 7,
            'stations' => array(
                1 => array( 'name' => "CCradio Ellak", 'url' => "http://stream.creativecommons.gr:8000/live0"),
                2 => array( 'name' => "Diesi 101.3", 'url' => "http://diesi.live24.gr/diesi1013"),
                3 => array( 'name' => "Dromos 89.8", 'url' => "http://dromos898.live24.gr/dromos898"),
                4 => array( 'name' => "Freedom 88.9", 'url' => "http://frontstage.iphost.gr:8016/stream"),
                5 => array( 'name' => "Melodia 99.2", 'url' => "http://netradio.live24.gr/melodia"),
                6 => array( 'name' => "Love Radio", 'url' => "http://loveradio.live24.gr/loveradio-1000"),
                7 => array( 'name' => "Parea 104", 'url' => "http://parea104.live24.gr/parea104"),
                )
        );
        $radio = maybe_serialize($radio);
        add_option('cdscr_radio_settings', $radio);
        parent::__construct(
            'codescar_radio_widget', // Base ID
            __('Codescar Radio Widget'), // Name
            array( 'description' => __( 'Radio player widget', 'Uses html5 audio element to play radio in sidebar' ),
            ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        $radio = get_option( 'cdscr_radio_settings' );
        $radio = maybe_unserialize($radio);
        $title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
        echo $args[ 'before_widget' ];
        ?>
        <div class="radio-widget">
            <div class="radio_block">
                <audio  src="<?php echo $instance[ 'default' ]; ?>" id="radio_player"
                        <?php echo ($instance[ 'auto' ]) ? 'autoplay="autoplay"': ''; ?> >
                </audio>
                <div id="radio_controls">
                    <div class="radio_cube">
                        <button type="button" id="radio_play">
                            <?php echo ($instance[ 'auto' ]) ? __( 'Pause' ) : __( 'Play' ); ?>
                        </button>
                        <button type="button" id="radio_mute"><?php echo __( 'Mute' ); ?></button>
                    </div>
                    <div class="radio_cube">
                        <input  type="range" id="radio_volume" min="0.1" max="1" step="0.1"
                                value="<?php echo $instance[ 'volume' ]; ?>">
                    </div>
                  </div>
            </div>
            <div class="radio_block">
                <select id="radio_stations">
                <?php 
                    foreach( $radio[ 'stations' ] as $station ){
                        $line = "<option value=\"{$station[ 'url' ]}\"";
                        $line = ( $station[ 'url' ] == $instance[ 'default' ] ) ? $line . " selected=\"selected\">" : $line . ">";
                        $line = $line."{$station[ 'name' ]}</option>\n\t\t\t\t";
                        echo $line;
                    }
                ?>
                </select>
            </div>
        </div>
        <?php
        echo $args['after_widget'];
    }

    /**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
    public function form( $instance ) {
        $radio = get_option( 'cdscr_radio_settings' );
        $radio = maybe_unserialize($radio);
        $instance = wp_parse_args( (array) $instance, $radio );
        ?>
        <p><fieldset class="basic-grey">
            <legend><?php echo __( 'Settings' ); ?>:</legend>
            <label>
                <span><?php echo __( 'Title' ); ?></span>
                <input  id="<?php echo $this->get_field_id( 'title' ); ?>" 
                        name="<?php echo $this->get_field_name('title'); ?>" type="text" 
                        value="<?php esc_attr_e($instance[ 'title' ]); ?>" />
            </label>
            <label>
                <span><?php echo __( 'Default' ); ?></span>
                <select id="<?php echo $this->get_field_id( 'default' ); ?>" 
                        name="<?php echo $this->get_field_name( 'default' ); ?>">
                    <?php 
                        foreach( $instance[ 'stations' ] as $station ){
                            $line = "<option value=\"{$station[ 'url' ]}\"";
                            $line = ( $station[ 'url' ] == $instance[ 'default' ] ) ? $line . " selected=\"selected\">" : $line . ">";
                            $line = $line."{$station[ 'name' ]}</option>\n\t\t\t\t";
                            echo $line;
                        }
                    ?>
                </select>
            </label>
            <label>
                <span><?php echo __( 'Volume' ); ?></span>
                0.1<input type="range" min="0.1" max="1" step="0.1"
                        id="<?php echo $this->get_field_id( 'volume' ); ?>" 
                        name="<?php echo $this->get_field_name( 'volume' ); ?>"
                        value="<?php echo $instance[ 'volume' ]; ?>">10
            </label>
            <label>
                <span><?php echo __( 'AutoPlay' ); ?></span>
                <input  type="checkbox" value="1" <?php if($instance[ 'auto' ]) echo 'checked="checked"'; ?>
                        id="<?php echo $this->get_field_id( 'auto' ); ?>" 
                        name="<?php echo $this->get_field_name( 'auto' ); ?>"
                        />
            </label>
        </fieldset></p>
		<?php
	}

    /**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags($new_instance[ 'title' ]);
        $instance[ 'default' ] = strip_tags($new_instance[ 'default' ]);
        $instance[ 'volume' ] = strip_tags($new_instance[ 'volume' ]);
        $instance[ 'auto' ] = ($new_instance[ 'auto' ]) ? strip_tags($new_instance[ 'auto' ]) : 0;
        return $instance;
    }
}

// Function registering the radio widget
function cdscr_register_radio_widget() {
    register_widget( 'Codescar_Radio_Widget' );
}
add_action( 'widgets_init', 'cdscr_register_radio_widget');

// Function registering radio widget scripts
function cdscr_register_radio_css_js() {
	wp_enqueue_script(
        'radio-script',
        plugins_url().'/radio-widget/radio-js.js',
        array( 'jquery' )
	);
    wp_enqueue_style( 
        'radio-style',
        plugins_url().'/radio-widget/radio-style.css'
    );
}
add_action( 'wp_enqueue_scripts', 'cdscr_register_radio_css_js' );
add_action( 'widgets_init', 'cdscr_register_radio_css_js');

// Function removing radio widget options
function cdscr_remove_options() {
    delete_option( 'cdscr_radio_settings' );
}
register_deactivation_hook( __FILE__, 'cdscr_remove_options' );
