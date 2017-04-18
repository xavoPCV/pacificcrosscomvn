<?php
defined('_JEXEC') or die('Restricted access');

class AdvisorLeadControllerAnalytics extends JControllerLegacy {

    function api_call() {

        $app = JFactory::getApplication();
        $input = $app->input;
        $request = $input->getString('request', '');
        $results = array();
        switch ($request) {
            case "show-all":
                $type = $input->get('type');
                $tracking_type = $input->get('tracking_type');
                $results = $this->analytics_show_all($type, $tracking_type);
                break;
            case "tracking":
                $request = JRequest::get();
                $this->tracking($request);
                break;
            case "chart":
                $request = JRequest::get();
                $results = $this->chart_data($request);
                break;
        }
        echo json_encode($results);

        $app->close();
    }

    function analytics_show_all($type, $tracking_type = '') {
        global $user;

        $html = '';
        $count = 0;
        $paged = 1;
        $per_page = 15;
        $timezone_offset = !empty($_POST['timezoneOffset']) ? $_POST['timezoneOffset'] : '';
        $cstr = !empty($_POST['cstr']) ? $_POST['cstr'] : '';
        $from = !empty($_POST['from']) ? $_POST['from'] : '';
        $until = !empty($_POST['until']) ? $_POST['until'] : '';
        $objects = '';
        $ctas = '';
        $pages = '';
        $more = false;

        if (!empty($cstr)) {
            $paged += $cstr;
        }

        $where = "WHERE uid = $user->id";
        $order = "ORDER BY optins DESC, uniques DESC LIMIT " . ($paged - 1) * $per_page . "," . $per_page;

        $model = $this->getModel('analytics');

        if (!empty($from) && !empty($until)) {

            if (!empty($tracking_type) && $tracking_type != 'all') {
                $total_results = $model->get_analytic_stats($tracking_type, $where, $from, $until);
            } else {
                $page_results = $model->get_analytic_stats('page', $where, $from, $until);
                $cta_results = $model->get_analytic_stats('cta', $where, $from, $until);
            }

            if (!empty($total_results)) {
                $total_results->from = strtotime("+$timezone_offset seconds", $from);
                $total_results->to = strtotime("+$timezone_offset seconds", $until);
            }

            $where .= ' HAVING optins > 0 OR uniques > 0';
        } else {

            if (!empty($tracking_type) && $tracking_type != 'all') {
                $total_results = $model->get_analytic_stats($tracking_type, $where);
            } else {
                $page_results = $model->get_analytic_stats('page', $where);
                $cta_results = $model->get_analytic_stats('cta', $where);
            }

            $where .= ' AND (uniques != 0 OR optins != 0)';
        }

        $total = 0;
        if ($type == 'details') {
            if (!empty($tracking_type) && $tracking_type != 'all') {
                $objects = $model->get_analytic_page_stats($tracking_type, $where, $order, $from, $until);
                $total = $model->get_analytic_total($tracking_type, $user->id);
            } else {
                $pages = $model->get_analytic_page_stats('page', $where, $order, $from, $until);
                $ctas = $model->get_analytic_page_stats('cta', $where, $order, $from, $until);

                $total_pages = $model->get_analytic_total('page', $user->id);
                $total_ctas = $model->get_analytic_total('cta', $user->id);
                $total = $total_ctas + $total_pages;
            }
        }

        if ($total > 0 || $type == 'stats') {
            if (!empty($tracking_type) && $tracking_type != 'all') {
                $analytics = $this->get_analytics_html($total_results, $type, $objects);
            } else {
                $page_analytics = $this->get_analytics_html($page_results, $type, $pages);
                $cta_analytics = $this->get_analytics_html($cta_results, $type, $ctas);
                $analytics['html'] = $page_analytics['html'] . $cta_analytics['html'];
                $analytics['count'] = 0;
            }
            $html = $analytics['html'];
            $count = $analytics['count'];
        }

        $last_page = $total / $per_page;
        if ($count == $per_page && $total > $per_page && $paged != $last_page)
            $more = 'true';

        $result = array(
            "status" => 200,
            "body" => array(
                "has_more" => $more, "cstr" => $paged, "html" => $html
            )
        );

        return $result;
    }

    function get_analytics_html($total_results, $type, $objects = array()) {
        $count = 0;

        $uniques = 0;
        $optins = 0;
        $total_conversion = 0;
        $total_date_text = '';
        $tracking_type_title = '';

        if (!empty($total_results)) {

            if ($total_results->tracking_type == 'page') {
                $tracking_type_title = 'Pages';
                $object_title = 'Page Name';
            } else {
                $tracking_type_title = 'CTAs';
                $object_title = 'CTA Name';
            }

            $uniques = $total_results->uniques;
            $optins = $total_results->optins;
            if ($uniques != 0) {
                $total_conversion = ($optins / $uniques) * 100;
            }

            if (!empty($total_results->from) && !empty($total_results->from)) {
                $today = date('m/d/Y', strtotime('today'));
                $yesterday = date('m/d/Y', strtotime('yesterday'));

                $from = date('m/d/Y', $total_results->from);
                $to = date('m/d/Y', $total_results->to);

                if ($today == $from)
                    $total_date_text = 'Total for today';
                else if ($yesterday == $from)
                    $total_date_text = "Total for yesterday";
                else
                    $total_date_text = "Total for this date range ($from - $to)";
            } else {

                $total_date_text = "Overall $tracking_type_title Stats";
            }
        }

        ob_start();
        if ($type == 'details' && !empty($objects)) {
            ?>
            <h3><?php echo $tracking_type_title ?> Stats</h3>
            <table id="page-table" class="table pages-table table-bordered table-hover">
                <thead>
                    <tr>
                        <th><?php echo $object_title ?></th>
                        <th>Uniques</th>
                        <th>Optins</th>
                        <th>Conversion Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($objects as $object) {

                        $conversion = 0;
                        if ($object->uniques != 0) {
                            $conversion = ($object->optins / $object->uniques) * 100;
                        }
                        ?>
                        <tr>
                            <td>
                                <div class="span7"><?php echo $object->name ?></div>
                                <div class="span2">
                                    <a href="#" class="set_page_id_analytics" tracking_type="<?php echo $total_results->tracking_type ?>" page_id="<?php echo $object->id ?>" page_name="<?php echo $object->name ?>">detailed analytics</a>
                                </div>
                            </td>
                            <td><?php echo $object->uniques ?></td>
                            <td><?php echo $object->optins ?></td>
                            <td><?php echo number_format($conversion, 2) ?>%</td>
                        </tr>
                        <?php
                        $count++;
                    }
                    ?>
                </tbody>
                <?php
                if (!empty($total_results)) {
                    ?>
                    <tfoot>
                        <tr>
                            <th><?php echo $total_date_text ?></th>
                            <th><?php echo $uniques ?></th>
                            <th><?php echo $optins ?></th>
                            <th><?php echo number_format($total_conversion, 2) ?>%</th>
                        </tr>
                    </tfoot>
                <?php } ?>
            </table>
            <?php
        } else if ($type == 'stats') {
            ?>
            <h3><?php echo $total_date_text ?></h3>
            <div class="row-fluid">
                <div class="span4 no_fixed">
                    <div class="panel">
                        <h3>Uniques</h3>
                        <span><?php echo $uniques ?></span>
                    </div>
                </div>
                <div class="span4 no_fixed">
                    <div class="panel">
                        <h3>Optins</h3>
                        <span><?php echo $optins ?></span>
                    </div>
                </div>
                <div class="span4 no_fixed">
                    <div class="panel">
                        <h3>Conversion Rate</h3>
                        <span><?php echo number_format($total_conversion, 2) ?>%</span>
                    </div>
                </div>
            </div>
            <?php
        }
        $html = ob_get_clean();
        $results = array('count' => $count, 'html' => $html);

        return $results;
    }

    function chart_data($queries) {
        $model = $this->getModel('analytics');
        $object_id = 0;
        $from = 0;
        $until = 0;
        $timezone = 0;
        $increment = 'hour';

        if (!empty($queries)) {
            $object_id = $queries['object_id'];
            $from = $queries['from'];
            $until = $queries['until'];
            $timezone = $queries['timezoneOffset'];
            $increment = $queries['increment'];
            $tracking_type = $queries['tracking_type'];
        }

        $body = array();
        $time = 3600;
        if ($increment == 'day')
            $time = 86400;
        $min = 0;
        $max = ($until - $from) / $time;
        $range = 1;
        for ($i = 0; $i < $max; $i+=$range) {
            $from_query = $from + $i * $time;
            $to_query = $from + ($i + $range) * $time;

            $views = $model->get_analytic_optins($object_id, 'views', $tracking_type, $from_query, $to_query);
            $uniques = $model->get_analytic_optins($object_id, 'uniques', $tracking_type, $from_query, $to_query);
            $optins = $model->get_analytic_optins($object_id, 'optins', $tracking_type, $from_query, $to_query);
            $rates = $uniques > 0 ? ($optins / $uniques) * 100 : 0;
            $hour = array();
            $hour[] = $from_query;
            $hour[] = (int) $views; //views
            $hour[] = (int) $optins; //opt-in
            $hour[] = (int) $uniques; //unique viewers
            $hour[] = (int) $rates; //conversion
            $body[] = $hour;
        }
        return array("status" => 200, "body" => $body);
    }

    function tracking($values) {

        $ip = AdvisorleadHelper::get_IP();
        $object_id = !empty($values['id']) ? $values['id'] : 0;
        $webinar_id = !empty($values['webinar_id']) ? $values['webinar_id'] : 0;
        $type = !empty($values['type']) ? $values['type'] : '';
        $email = !empty($values['email']) ? $values['email'] : '';
        $name = !empty($values['name']) ? $values['name'] : '';
        $phone = !empty($values['phone']) ? $values['phone'] : '';
        $tracking_type = !empty($values['tracking_type']) ? $values['tracking_type'] : 'page';

        if ($tracking_type == 'page') {
            $object_table = PAGES_TABLE_UNQUOTE;
        } else {
            $object_table = CTA_TABLE_UNQUOTE;
        }

        if (!empty($email))
            $_SESSION['email'] = $email;
        if (!empty($name))
            $_SESSION['name'] = $name;

//        if ($webinar_id > 0 && $tracking_type == 'page') {
//            $email_services = get_option('email_services');
//            $user_ID = $wpdb->get_var("SELECT uid FROM $object_table WHERE id = $object_id");
//            $param = array(
//                'name' => $name,
//                'email' => $email,
//                'phone' => $phone
//            );
//            $this->create_registrant_gotowebinar($user_ID, $email_services, $webinar_id, $param);
//        }

        $model = $this->getModel('analytics');
        $last_view_time = $model->get_last_view_time($object_id);
        $check_view_ip = $model->get_view_ip($object_id);

        if (($type == 'view' && (time() - $last_view_time > 300 || !in_array($ip, $check_view_ip))) || $type == 'optin') {
            $model->save_tracking_data($object_id, $type, $email, $ip, $tracking_type);
        }

        //UPDATE OBJECT TABLE
        $views = $model->get_analytic_optins($object_id, 'views', $tracking_type);
        $uniques = $model->get_analytic_optins($object_id, 'uniques', $tracking_type);
        $optins = $model->get_analytic_optins($object_id, 'optins', $tracking_type);
        $rates = $uniques > 0 ? ($optins / $uniques) * 100 : 0;

        $model->save_object_tracking_data($object_table, $object_id, $views, $uniques, $optins, $rates);

        //CREATE PIXEL IMAGE
        $im = imagecreate(1, 1);
        $white = imagecolorallocate($im, 255, 255, 255);
        imagesetpixel($im, 1, 1, $white);
        header('Content-Type: image/gif');
        imagejpeg($im);
        imagedestroy($im);
    }

}