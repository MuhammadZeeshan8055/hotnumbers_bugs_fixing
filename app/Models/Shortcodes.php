<?php

namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;
use function Composer\Autoload\includeFile;


class Shortcodes extends Model {

    public function run($html) {
        $codes = ['post-grids','post-grids2'];
        $scodeList = [];

        preg_match_all("/\[[^\]]*\]/", $html, $matches);
        foreach($matches[0] as $match) {
            foreach($codes as $code) {
                $mk = explode(' ',$match)[0];
                if(explode(' ',$match)[0] !== '['.$code) {
                    continue;
                }
                $matchKeys = str_replace("[$code ",'',$match);
                $matchKeys = trim($matchKeys,']');
                $parts = explode('" ',$matchKeys);
                preg_match_all('/"([^"]+)"/', $matchKeys, $atts);
                $atts_list = [];
                foreach($atts[1] as $k=>$m) {
                    $part = $parts[$k];
                    $part = strstr($part,'="',true);
                    $part = trim($part,'[]');
                    $atts_list[$part] = $m;
                }

                if(!empty($atts_list)) {
                    $atts_list['shortcode'] = $match;
                    $scodeList[trim($mk,'[]')][] = $atts_list;
                }
            }
        }

        if(!empty($scodeList)) {

            $media = model('Media');
            $productModel = model('ProductsModel');
            foreach($scodeList as $id=>$atts) {
                foreach($atts as $att) {
                    if ($id == "post-grids") {
                        $postIDs = explode(',', $att['ids']);
                        if(!empty($postIDs)) {
                            $newhtml = '';
                            foreach($postIDs as $id) {
                                ob_start();
                                $data = $this->db->table('tbl_products')->where('id',$id)->get()->getRow();
                                $path = getcwd().'\app\views\includes\product-box-1.php';
                                if(file_exists($path)) {
                                   include $path;
                                }
                                $newhtml .= ob_get_clean();
                            }
                            if($newhtml) {
                                $html = str_replace($att['shortcode'],'<div class="featured_product_boxes">'.$newhtml.'</div>',$html);
                            }
                        }
                    }
                }
            }

        }

        return $html;
    }
}