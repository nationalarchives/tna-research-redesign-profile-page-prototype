<?php
/**
 * Created by PhpStorm.
 * User: mdiaconita
 * Date: 06/06/2017
 * Time: 18:46
 */

function profile_landing_page_box($post)
{
    $blurb_one = get_post_meta($post->ID, 'profile_page_receipt_blurb', true);
    $blurb_two = get_post_meta($post->ID, 'profile_page_receipt_blurb_two', true);
    $args = array(
        'media_buttons' => false,
        'textarea_rows' => 3,
        'tinymce' => false,
        'quicktags' => array('buttons' => 'strong,em,ul,ol,li,link'),
        'wpautop' => false
    );
    wp_nonce_field(basename(__FILE__), 'profile_page_receipt_content_nonce');
    ?>
    <table class="profile-page-table">
        <tbody>
        <tr>
            <th style="width:20%">
                <label for="profile_page_receipt_blurb">Blurb one</label>
            </th>
            <td>
                <?php wp_editor($blurb_one, 'profile_page_receipt_blurb', $args); ?>
                <p>The text entered here will appear at the top of profile page landing page.</p>
            </td>
        </tr>
        <tr>
            <th style="width:20%">
                <label for="profile_page_receipt_blurb">Blurb Two</label>
            </th>
            <td>
                <?php wp_editor($blurb_two, 'profile_page_receipt_blurb_two', $args); ?>
                <p>The text entered here will appear at the top of profile page landing page.</p>
            </td>
        </tr>
        </tbody>
    </table>
    <?php
}

function profile_page_meta_box_save($post_id)
{
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);

    $is_valid_cf_receipt_email_nonce = (isset($_POST['profile_page_receipt_content_nonce']) && wp_verify_nonce($_POST['profile_page_receipt_content_nonce'], basename(__FILE__))) ? 'true' : 'false';
    $is_valid_cf_get_tna_email_nonce = (isset($_POST['profile_page_receipt_content_nonce']) && wp_verify_nonce($_POST['profile_page_receipt_content_nonce'], basename(__FILE__))) ? 'true' : 'false';
    if ($is_autosave || $is_revision || !$is_valid_cf_receipt_email_nonce || !$is_valid_cf_get_tna_email_nonce) {
        return;
    }
    $allowed = array(
        'a' => array(
            'href' => array(),
            'title' => array()
        ),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
        'p' => array(),
        'ul' => array(),
        'li' => array(),
        'ol' => array()
    );
    if (isset($_POST['profile_page_receipt_blurb'])) {
        update_post_meta($post_id, 'profile_page_receipt_blurb', wp_kses($_POST['profile_page_receipt_blurb'], $allowed));
    }
    if (isset($_POST['profile_page_receipt_blurb_two'])) {
        update_post_meta($post_id, 'profile_page_receipt_blurb_two', esc_html($_POST['profile_page_receipt_blurb_two']));
    }
}