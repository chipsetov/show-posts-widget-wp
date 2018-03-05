<?php
/**
 * Created by PhpStorm.
 * User: AntoA
 * Date: 01.03.2018
 * Time: 17:54
 */

/**
 * Adds Foo_Widget widget.
 */
class Foo_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'foo_widget', // Base ID
            esc_html__( 'Widget Title', 'text_domain' ), // Name
            array( 'description' => esc_html__( 'A Foo Widget', 'text_domain' ), ) // Args
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
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
//        echo $instance['count'];
//        echo $instance['order'];
//        echo esc_html__( 'Hello, World!', 'text_domain' );
        echo $args['after_widget'];

//        $query = new WP_Query(array(
//                array(
//                    'post_limits' => $instance['count'],
//                    'orderby'    => $instance['order'],
//                    'order'    => $instance['order'])
//            )
//        );

        $query = new WP_Query( array( 'orderby' => $instance['order'], 'order' => $instance['order'], 'posts_per_page' => $instance['count'] ) );
        while ( $query->have_posts() ) {
            $query->the_post();

            the_title('<h1>', '</h1>');
            the_post_thumbnail();
        }
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
        $count = ! empty( $instance['count'] ) ? $instance['count'] : esc_html__( 3, 'text_domain' );
        $order = ! empty( $instance['order'] ) ? $instance['order'] : esc_html__( RAND, 'text_domain' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_attr_e( 'count:', 'text_domain' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" value="<?php echo esc_attr( $count ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_attr_e( 'order:', 'text_domain' ); ?></label>
<!--            <input class="widefat" id="--><?php //echo esc_attr( $this->get_field_id( 'order' ) ); ?><!--" name="--><?php //echo esc_attr( $this->get_field_name( 'order' ) ); ?><!--" type="number" value="--><?php //echo esc_attr( $order ); ?><!--">-->

            <select id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" >
                <option <?php if(esc_attr( $order ) == "asc") echo "selected"; ?> value="asc">ASC</option>
                <option <?php if(esc_attr( $order ) == "desc") echo "selected"; ?> value="desc">DESC</option>
                <option <?php if(esc_attr( $order ) == "rand") echo "selected"; ?> value="rand">RAND</option>
            </select>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['count'] = ( ! empty( $new_instance['count'] ) ) ? strip_tags( $new_instance['count'] ) : '';
        $instance['order'] = ( ! empty( $new_instance['order'] ) ) ? strip_tags( $new_instance['order'] ) : '';

        return $instance;
    }

} // class Foo_Widget

// register Foo_Widget widget
function register_foo_widget() {
    register_widget( 'Foo_Widget' );
}
add_action( 'widgets_init', 'register_foo_widget' );
