<?php

class ModelModuleGeneratorcatalog extends Model
{
    //Ordinamento array multidimensionale
    function array_msort($array, $cols)
    {
        $colarr = array();
        foreach ($cols as $col => $order) {
            $colarr[$col] = array();
            foreach ($array as $k => $row) {
                $colarr[$col]['_' . $k] = strtolower($row[$col]);
            }
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\'' . $col . '\'],' . $order . ',';
        }
        $eval = substr($eval, 0, -1) . ');';
        eval($eval);
        $ret = array();
        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k, 1);
                if (!isset($ret[$k])) $ret[$k] = $array[$k];
                $ret[$k][$col] = $array[$k][$col];
            }
        }
        return $ret;
    }

    //Query all product
    function all_products()
    {
        $sql = 'SELECT product_id,name FROM ' . DB_PREFIX . 'product_description ORDER BY name ASC';
        $query = $this->db->query($sql);
        return $query->rows;
    }

    //Product to category
    function product_to_category($category)
    {
        $result = $this->db->query("SELECT* FROM product_to_category WHERE category_id = '$category'");
        foreach ($result->rows as $row) {
            $product = $this->db->query("SELECT name,referrerid,image,quantity,stock,stato_conservazione,price,cost,supplier  FROM product_description,product  WHERE product_description.product_id = '$row[product_id]' AND product.product_id = '$row[product_id]'");
            foreach ($product->rows as $prod) {
                $prodotti[] = array(
                    'name' => $prod['name'],
                    'referrer' => $prod['referrerid'],
                    'image' => pathinfo($prod['image'], PATHINFO_DIRNAME) . "/" . pathinfo($prod['image'], PATHINFO_FILENAME) . "-40x40." . pathinfo($prod['image'], PATHINFO_EXTENSION),
                    'stock' => $prod['stock'],
                    'quantity' => $prod['quantity'],
                    'stato_conservazione' => $prod['stato_conservazione'],
                    'price' => $prod['price'],
                    'cost' => $prod['cost'],
                    'fornitore' => $prod['supplier'],
                    'product_id' => $row['product_id']
                );
            }
        }
        return $this->array_msort( $prodotti, array('referrer' => SORT_ASC));
    }

    //Query all category one product
    function all_categories()
    {
        $result = $this->db->query("SELECT product_id, CONCAT(GROUP_CONCAT(category_id SEPARATOR '||')) AS categories FROM `" . DB_PREFIX . "product_to_category` GROUP BY product_id ORDER BY product_id ASC");
        $tmp = array();
        foreach ($result->rows as $row) {
            $tmp[$row['product_id']] = $row['categories'];
        }
        return $tmp;
    }

    //Category tree
    function getCategories($parent_id = 0, $level = 0)
    {
        $categories = array();
        $result = $this->db->query(
            "SELECT cd.category_id, cd.name FROM " . DB_PREFIX . "category_description cd LEFT JOIN " . DB_PREFIX . "category c ON c.category_id = cd.category_id WHERE c.parent_id = " . (int)$parent_id . " AND cd.language_id = " . $this->config->get('config_language_id') . " ORDER BY cd.name ASC"
        );
        if ($result->rows) {
            foreach ($result->rows as $row) {
                $categories[$row['category_id']] = array(
                    'name' => $row['name'],
                    'category_id' => $row['category_id'],
                    'level' => $level + 1
                );
                $subcats = $this->getCategories($row['category_id'], $level + 1);
                if (!empty($subcats)) {
                    foreach ($subcats as $subcat) {
                        $categories[$subcat['category_id']] = $subcat;
                    }
                }
            }
        }
        return $categories;
    }

    //copia cartella
    function copyfolder($source, $dest)
    {
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }
        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }
        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest);
        }
        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            // Deep copy directories
            $this->copyfolder("$source/$entry", "$dest/$entry");
        }
        // Clean up
        $dir->close();
        return true;
    }
}/*fine class*/
?>