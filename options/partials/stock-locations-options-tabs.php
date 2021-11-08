<?php

$tabs = stock_locations_options_get_tabs();
?>

<h2 class="nav-tab-wrapper">

    <?php

    foreach ($tabs as $tab => $name) :
        $class = ($tab == stock_locations_options_get_tab()) ? 'nav-tab-active' : '';
    ?>
        <a class="nav-tab <?php esc_attr_e($class) ?>" href="<?php esc_attr_e(add_query_arg(compact("tab"))) ?>"><?php esc_html_e($name) ?></a>
    <?php
    endforeach;
    ?>
</h2>