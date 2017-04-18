<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class AdvisorLeadControllerAdvisorlead extends JControllerLegacy {

    function api_call() {

        $app = JFactory::getApplication();
        $input = $app->input;
        $request = $input->getString('request', '');
        $results = array();
        switch ($request) {
            case "my-images":
                $is_html = $input->get('is_html');
                $results = $this->get_user_images_content($is_html);
                break;
        }
        echo json_encode($results);

        $app->close();
    }

    function get_user_images_content($is_html = 0) {
        global $user;

        $results = array(
            'status' => 200,
            'body' => array('has_more' => false, 'cstr' => '')
        );

        $model = $this->getModel('Dashboard');
        $images = $model->get_user_images($user->id);
        if ($is_html) {
            if (!empty($images)) {
                ob_start();
                ?>
                <div id="page-table" class="paginator-ul">
                    <ul id="my-images" class="unstyled">
                        <?php
                        foreach ($images as $image) {
                            ?>
                            <li>
                                <span class="image-block">
                                    <span class="image-space"><img src="<?php echo $image->url ?>" alt="Image"></span>
                                    <div class="caption">
                                        <small>
                                            <?php echo date('m/d/Y', $image->created) ?>
                                            <a href="<?php echo ADVISORLEAD_URL . "/dashboard/images/$image->id/delete" ?>" class="btn btn-danger btn-mini fright delete_btn">
                                                <i class="fa fa-trash-o icon-white"></i>
                                            </a>
                                        </small>
                                    </div>
                                </span>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <?php
                $html = ob_get_clean();
            } else {
                $html = '<h6 style="text-align: center">No image found!</h6>';
            }

            $results['body']['html'] = $html;
        } else {
            $results['body']['images'] = $images;
        }

        return $results;
    }

}
?>