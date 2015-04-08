<?php

class ControllerModuleGeneratorcatalog extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('module/generatorcatalog');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');
        $this->load->model('module/generatorcatalog'); //add model
        $this->document->addStyle('view/stylesheet/generator_catalog/gcatalog.css');// add style
        $this->document->addStyle('view/stylesheet/generator_catalog/selectBox.css');// add style
        $this->document->addScript('view/javascript/generator_catalog/gcatalog.js');// add script
        $this->document->addScript('view/javascript/generator_catalog/selectBox.js');// add script
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('generatorcatalog', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['product_text'] = $this->language->get('product_text');
        $this->data['delete_text'] = $this->language->get('delete_text');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/generatorcatalog', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['filter_text'] = $this->language->get('filter_text');

        /****************Query category and product***************/
        $all_prod = $this->model_module_generatorcatalog->all_products();
        $this->data['array_prod'] = $all_prod;
        $all_categories = $this->model_module_generatorcatalog->getCategories();
        $this->data['array_categories'] = $all_categories;
        $this->data['token'] = $this->session->data['token'];
        /*****************/
        $this->data['action'] = $this->url->link('module/generatorcatalog', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['modules'] = array();
        if (isset($this->request->post['generatorcatalog_module'])) {
            $this->data['modules'] = $this->request->post['generatorcatalog_module'];
        } elseif ($this->config->get('generatorcatalog_module')) {
            $this->data['modules'] = $this->config->get('generatorcatalog_module');
        }
        $this->load->model('design/layout');
        $this->data['layouts'] = $this->model_design_layout->getLayouts();
        $this->template = 'module/generatorcatalog.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'module/generatorcatalog')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    //prodotti sotto categorie chiamate
    public function products_to_category($category_id, $level)
    {
        $this->load->language('module/generatorcatalog');
        $this->load->model('module/generatorcatalog');
        if (!empty($category_id)) {
            $category_id = $category_id;
            $level = $level;
        } else {
            $category_id = $_GET['category'];
            $level = $_GET['level'];
        }
        $categories = array();
        $categorie = $this->model_module_generatorcatalog->getCategories($category_id);
        foreach ($categorie as $cat) {
            if ($cat['level'] == $level) {
                $categories[$cat['name']] = $cat;
            }
        }
        sort($categories);

        $table = "<div class='table-container'>";
        $table .= "<div class='fixed-header'>";
        $table .= "<div class='centered'>";
        $table .= "<table class='product-table'>";
        $table .= "<thead>";
        $table .= "<th class='name-title'>Nome</th>";
        $table .= "<th class='reserve-title'>Conservazione</th>";
        $table .= "<th class='referrer-title'>Referrer</th>";
        $table .= "<th class='quantity-title'>Quantità</th>";
        $table .= "<th class='reserve-title'>Riserve</th>";
        $table .= "<th class='reserve-title'>Prezzo</th>";
        $table .= "<th class='reserve-title'>Costo</th>";
        $table .= "<th class='reserve-title'>Fornitore</th>";
        $table .= "</thead>";
        $table .= "<thead class='filter-row'>";
        $table .= "<td colspan='9' class='search-row'><input type='text' data-match='name' placeholder='Filtra la tabella' class='search filter-input name-filter' /></td>";
        $table .= "</thead>";
        $table .= "</table>";
        $table .= "</div>";
        $table .= "</div>";
        $i = 0;
        $table .= "<div class='table-box'>";
        $table .= "<table class='product-table'>";
        foreach ($categories as $cat) {
            $prod = $this->model_module_generatorcatalog->product_to_category($cat['category_id']);
            $table .= "<thead>";
            $table .= "<th class='category-name'>$cat[name]</th>";
            $table .= "<th class='reserve-title'>Conservazione</th>";
            $table .= "<th class='referrer-title'>Referrer</th>";
            $table .= "<th class='quantity-title'>Quantità</th>";
            $table .= "<th class='reserve-title'>Riserve</th>";
            $table .= "<th class='reserve-title'>Prezzo</th>";
            $table .= "<th class='reserve-title'>Costo</th>";
            $table .= "<th class='reserve-title'>Fornitore</th>";
            $table .= "</thead>";
            if ($i == 0) {
                $table .= "<thead class='filter-row'>";
                $table .= "<td colspan='9' class='search-row'><input type='text' data-match='name' placeholder='Filtra la tabella' class='search filter-input name-filter' /></td>";
                $table .= "</thead>";
            }
            $table .= "<tbody class='rows'>";
            $i++;
            foreach ($prod as $prodotto) {
                $table .= "<tr>";
                $table .= "<td class='name-row'>" . $prodotto['name'] . "</td>";
                $table .= "<td class='name-row'>" . $prodotto['stato_conservazione'] . "</td>";
                $table .= "<td class='referrer-row'>" . $prodotto['referrer'] . "</td>";
                $table .= "<td>" . $prodotto['quantity'] . "</td>";
                $table .= "<td>" . $prodotto['stock'] . "</td>";
                $table .= "<td>" . $prodotto['price'] . "</td>";
                $table .= "<td>" . $prodotto['cost'] . "</td>";
                $table .= "<td>" . $prodotto['fornitore'] . "</td>";
                $table .= "</tr>";
            }
        }
        $table .= "</tbody>";
        $table .= "</table>";
        $table .= "</div>";
        $table .= "</div>";
        echo $table;
    }

    public function products_to_category_catalog(/*$category_name = null,$category_id, $level*/)
    {
        $this->load->language('module/generatorcatalog');
        $this->load->model('module/generatorcatalog');

        $category_id = $_GET['category'];
        $level = $_GET['level'];
        $category_name = $_GET['category_name'];

        $categories = array();
        $categorie = $this->model_module_generatorcatalog->getCategories($category_id);
        $nodo = array();
        foreach ($categorie as $cat) {
            if ($cat['level'] == $level) {
                $categories[] = $cat;
            }
        }
        //Ordino l'array
        sort($categories);
        foreach ($categories as $cat) {
            $prod = $this->model_module_generatorcatalog->product_to_category($cat['category_id']);
            foreach ($prod as $prodotto) {
                $nodo[$cat['name']][$prodotto['name']] = array(
                    "nome" => $prodotto['name'],
                    "stato_conservazione" => $prodotto['stato_conservazione'],
                    "referrer" => $prodotto['referrer'],
                    'stock' => $prodotto['stock'],
                    'quantity' => $prodotto['quantity'],
                    'stato_conservazione' => $prodotto['stato_conservazione'],
                    'price' => $prodotto['price'],
                    'cost' => $prodotto['cost'],
                    'fornitore' => $prodotto['fornitore'],
                    'product_id' => $prodotto['product_id']
                );
            }
        }
        $this->create_catalog($nodo);
    }

    //Zip cartella
    public function zipData($source, $destination)
    {
        if (extension_loaded('zip')) {
            if (file_exists($source)) {
                $zip = new ZipArchive();
                if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
                    $source = realpath($source);
                    if (is_dir($source)) {
                        $iterator = new RecursiveDirectoryIterator($source);
                        // skip dot files while iterating
                        $iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
                        $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
                        foreach ($files as $file) {
                            $file = realpath($file);
                            if (is_dir($file)) {
                                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                            } else if (is_file($file)) {
                                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                            }
                        }
                    } else if (is_file($source)) {
                        $zip->addFromString(basename($source), file_get_contents($source));
                    }
                }
                return $zip->close();
            }
        }
        return false;
    }

    //Delete folder
    public function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    //Creazione file catalogo
    public function create_catalog($json)
    {
        //Contenuto passato via ajax
        $level = $_GET['level'];
        $category = $_GET['category'];
        $category_name = $_GET['category_name'];
        $name_folder = str_replace(" ","_",$_GET['name_page']);

        //Chiamo il modulo con le funzioni
        $this->load->model('module/generatorcatalog');

        //Creo la cartella se non esiste
        if (!file_exists("../Custom_Catalogs")) {
            mkdir("../Custom_Catalogs", 0755);
        }

        if (!file_exists("../Catalogs_Archives")) {
            mkdir("../Catalog_Archives", 0755);
        }

        //Nome cartella
        $date = str_replace(" ","_",date('d-m-Y H:i:s'));
        $name_folder = $name_folder . "-" . $date;

        //Duplico il template
        $this->model_module_generatorcatalog->copyfolder("view/stylesheet/generator_catalog/template", "../Custom_Catalogs/" . $name_folder);

        //Creo e salvo il json
        $db = "var name_catalog = \"".$category_name."\";var db = ".json_encode($json);
        file_put_contents('../Custom_Catalogs/'.$name_folder.'/js/db.js', $db);

        //Zippo la cartella e la elimino
        $this->zipData('../Custom_Catalogs/' . $name_folder, '../Catalog_Archives/' . $name_folder . '.zip');
        $this->deleteDir("../Custom_Catalogs/". $name_folder);
    }

    //Archivio cataloghi
    public function catalog_archive()
    {
        $path_archive = "../Catalog_Archives/";
        $files1 = scandir($path_archive);
        $list = "";
        foreach ($files1 as $file) {
            if ($file != "." && $file != "..") {
                $list .= "<li>";
                $list .= "<a class='download-link' data-link-rel='../Catalog_Archives/".$file."' href='" . HTTP_CATALOG . "Catalog_Archives/" . $file . "' download='".$file."'></a>";
                $list .= "<a class='delete-link' data-link-rel='../Catalog_Archives/".$file."' href='#'></a>";
                $list .= $file;
                $list .= "</li>";
            }
        }
        echo $list;
    }

    //Delete archivio
    public function delete_catalog(){
        unlink($_GET["file"]);
    }
}

?>