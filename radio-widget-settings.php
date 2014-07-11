<?php
class Codescar_Radio_Widget_Settings {
    private $options;

    public function __construct() {
        add_action( 'admin_menu', array( &$this, 'add_plugin_page' ) );
    }

    public function add_plugin_page() {
        add_options_page(
            'Codescar Radio Stations', 
            'Codescar Radio Widget Options', 
            'manage_options', 
            'codescar-radio-widget', 
            array( $this, 'cdscr_options_page' )
        );
    }

    public function cdscr_options_page() {
        $settings_url = admin_url( 'options-general.php' ) . '?page=codescar-radio-widget';

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You do not have sufficient permissions to access this page.', 'codescar-radio-widget' ) );
        }

        echo '<div class="wrap">'
             .'<h2><div id="icon-options-general" class="icon32"></div>'
             .__( 'Codescar Radio Widget Stations', 'codescar-radio-widget' ).'</h2>';
        $radio = get_option( 'cdscr_radio_settings' );
        $radio = maybe_unserialize( $radio );

        if ( isset( $_POST[ 'cdscr_add_new' ] ) && !isset( $_POST[ 'station_id' ] ) ){
            if ( !isset( $_POST[ 'station_name' ] ) || empty( $_POST[ 'station_name' ] )
            || !isset( $_POST[ 'station_url' ] ) || empty( $_POST[ 'station_url' ] ) ) {
                echo '<div id="message" class="error"><p>'. __( 'Please fill all the boxes!', 'codescar-radio-widget' ) .'</p></div>';
            } else {
                $tmp = array();
                $tmp[ 'name' ] = $_POST[ 'station_name' ];
                $tmp[ 'url' ] = $_POST[ 'station_url' ];
                $radio[ 'stations' ][] = $tmp;
                var_dump("boalfmoa");
                update_option( 'cdscr_radio_settings', $radio );
            }
        }
        if ( isset( $_POST[ 'station_id' ] ) ) {
            if ( !isset( $_POST[ 'station_name' ] ) || empty( $_POST[ 'station_name' ] ) 
                || !isset( $_POST[ 'station_url' ] ) || empty( $_POST[ 'station_url' ] ) ) {
                    echo '<div id="message" class="error"><p>'
                            . __( 'Please fill all the boxes!', 'codescar-radio-widget' ) .'</p></div>';
            } else {
                $tmp = array();
                $tmp[ 'name' ] = $_POST[ 'station_name' ];
                $tmp[ 'url' ] = $_POST[ 'station_url' ];
                $rm_id = $_POST[ 'station_id' ];
                $radio[ 'stations' ][ $rm_id ] = $tmp;
                update_option( 'cdscr_radio_settings', $radio );
            }
        }
        if ( isset( $_GET[ 'action' ] ) ) {
            if ( !isset( $_GET[ 'id' ] ) || empty( $_GET[ 'id' ] ) ) {
                echo '<div id="message" class="error"><p>'. __( 'There was an error!', 'codescar-radio-widget' ) .'</p></div>';
            } elseif ( $_GET[ 'action' ] == "delete" ) {
                //delete the record
                $rm_id = $_GET[ 'id' ] - 1;
                unset( $radio[ 'stations' ][ $rm_id ] );
                $radio[ 'stations' ] = array_values( $radio[ 'stations' ] );
                update_option( 'cdscr_radio_settings', $radio );
            } elseif ( $_GET[ 'action' ] == "edit" ) {
                $rm_id = $_GET[ 'id' ] - 1;
                $station_to_edit = $radio[ 'stations' ][ $rm_id ];
                $station_to_edit[ 'id' ] = $rm_id;
            }
        }
        echo '<table class="widefat">
                <thead>
                    <tr>
                        <th>'. __( 'ID', 'codescar-radio-widget' ).'</th>
                        <th>'. __( 'Name', 'codescar-radio-widget' ).'</th>
                        <th>'. __( 'Url', 'codescar-radio-widget' ).'</th>
                        <th>'. __( 'Action', 'codescar-radio-widget' ).'</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>'. __( 'ID', 'codescar-radio-widget' ).'</th>
                        <th>'. __( 'Name', 'codescar-radio-widget' ).'</th>
                        <th>'. __( 'Url', 'codescar-radio-widget' ).'</th>
                        <th>'. __( 'Action', 'codescar-radio-widget' ).'</th>
                    </tr>
                </tfoot>
                <tbody>';
        $id = 1;
        foreach( $radio[ 'stations' ] as $station ){
            echo '<tr>
                     <td>'. $id .'</td>
                     <td>'. $station[ 'name' ] .'</td>
                     <td><a class="edit_link" target="_blank" href="'. $station[ 'url' ] .'">'. $station[ 'url' ] .'</a></td>
                     <td>
                        <a class="edit_link" href="'. $settings_url .'&action=edit&id='. $id .'">'
                            . __( 'Edit', 'codescar-radio-widget' ) .'</a> | 
                        <a class="delete_link" href="'. $settings_url .'&action=delete&id='. $id .'">'
                            . __( 'Delete', 'codescar-radio-widget' ) .'</a></td>
                </tr>';
            $id++;
        }
        $flag = isset( $station_to_edit );
        echo '<tr><form method="POST" action="'. $settings_url .'">';
        if ($flag) {
            echo '<td>'. __( 'Editing', 'codescar-radio-widget') .'</td>';
        } else {
            echo '<td>'. __( 'New', 'codescar-radio-widget') .'</td>';
        }
        echo '<td><input type="text" maxlength="45" style="width: 85%;" size="10" value="';
        echo ($flag) ? $station_to_edit[ 'name' ] : '';
        echo '" id="station_name" name="station_name" /></td>
              <td><input type="text" maxlength="200" style="width: 85%;" value="';
        echo ($flag) ? $station_to_edit[ 'url' ] : '';
        echo '" id="station_url" name="station_url" /></td>
              <td><input name="save" type="submit" class="button-primary" value="'. __( 'Save', 'codescar-radio-widget' ) .'" />';
        if ($flag) {
            echo '<input type="hidden" name="station_id" value="'. $station_to_edit[ 'id' ] .'" />';
        }
        echo '<input type="hidden" name="cdscr_add_new" value="1" /></td>
              </tbody></table></div>';
        echo '</div>';
    }
}
