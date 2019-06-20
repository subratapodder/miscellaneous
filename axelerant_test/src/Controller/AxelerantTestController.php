<?php
/*
 * Controller Creator : Subrata
 * Use to return JSON output for a specific node
 *
 */
namespace Drupal\axelerant_test\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

class AxelerantTestController extends ControllerBase {
    public function getJsonDataByNid($api_key, $nid) {
        $site_config = $this->config('system.site');
        $site_api_key = $site_config->get('siteapikey');
        $json_array = array(
            'data' => array()
        );

        if ($site_api_key && $api_key && $api_key == $site_api_key) {
            $node_detail = Node::load($nid);
            if (!empty($node_detail)) {
                $node_type  = $node_detail->getType();
                if ($node_type == 'page') {
                    $json_array['data'][] = array(
                        'type' => $node_type,
                        'id' => $node_detail->get('nid')->value,
                        'attributes' => array(
                            'title' =>  $node_detail->get('title')->value,
                            'content' => $node_detail->get('body')->value,
                        ),
                    );
                    return new JsonResponse($json_array);
                }
            }
        }
        throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
    }
}