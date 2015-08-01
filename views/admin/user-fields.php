<?php
$WpAuthorInfo = $GLOBALS[ 'WpAuthorInfo' ];
$lang = $WpAuthorInfo->getVar('lang');
$settings = WpAuthorInfo::getVar('settings');
$availFields = WpAuthorInfo::getVar('fields');
?>
<h3>Wp Author Info <?php _e( 'User Details', $lang ); ?></h3>
<table class="form-table">
    <?php foreach ($availFields as $field) {
        if (!in_array($field['field'], $settings['visibile_fields'])){
            continue;
        }
        $fn = 'wpai_'.$field['field'];
    ?>
    <tr>
        <th><label for="<?php echo $fn;?>"><?php echo $field['name'];?></label></th>
        <td>
            <input type="text" name="<?php echo $fn;?>" id="<?php echo $fn;?>" value="<?php echo esc_attr( get_the_author_meta( $fn, $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e( $field['help'], $lang ); ?></span>
        </td>
    </tr>
    <?php } ?>
</table>