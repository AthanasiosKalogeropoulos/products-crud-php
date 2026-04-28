<?php
class Products
{

    private $xml_file_path = '';

    public function __construct($xml_file_path = '')
    {
        $this->xml_file_path = $xml_file_path;
    }

    /**
     * This function prints HTML tr rows for all products as read from the xml file
     * @return void
     */
    public function print_html_table_with_all_products()
    {
        $xmldata = simplexml_load_file($this->xml_file_path) or die("Failed to load");
        $xml_data = $xmldata->children();

        foreach ($xml_data->PRODUCTS->PRODUCT as $key => $prod) {
            $this->print_html_of_one_product_line($prod);
        }
    }

    /**
     * This function prints an HTML tr for a given product
     * @param mixed $prod It is the product object as retrieved from the xml file
     * @return void
     */
    private function print_html_of_one_product_line($prod)
    {
        $instock = trim((string)$prod->INSTOCK);
        $badge_class = $instock === 'Y' ? 'badge-green' : 'badge-gray';
        $badge_label = $instock === 'Y' ? 'Yes' : 'No';

        echo "<tr>";
        echo "<td>" . htmlspecialchars(trim((string)$prod->NAME)) . "</td>";
        echo "<td class='price'>€" . htmlspecialchars(trim((string)$prod->PRICE)) . "</td>";
        echo "<td>" . htmlspecialchars(trim((string)$prod->QUANTITY)) . "</td>";
        echo "<td>" . htmlspecialchars(trim((string)$prod->CATEGORY)) . "</td>";
        echo "<td>" . htmlspecialchars(trim((string)$prod->MANUFACTURER)) . "</td>";
        echo "<td class='price'>" . htmlspecialchars(trim((string)$prod->BARCODE)) . "</td>";
        echo "<td>" . htmlspecialchars(trim((string)$prod->WEIGHT)) . "</td>";
        echo "<td><span class='badge {$badge_class}'>{$badge_label}</span></td>";
        echo "<td>" . htmlspecialchars(trim((string)$prod->AVAILABILITY)) . "</td>";
        echo "</tr>";
    }

    /**
     * This function adds a new product to the xml file
     * @param array $data Associative array with product fields
     * @return void
     */
    public function add_product($data)
    {
        $xmldata = simplexml_load_file($this->xml_file_path) or die("Failed to load");

        $new_product = $xmldata->PRODUCTS->addChild('PRODUCT');
        $new_product->addChild('NAME', htmlspecialchars(trim($data['name'])));
        $new_product->addChild('PRICE', htmlspecialchars(trim($data['price'] ?? '')));
        $new_product->addChild('QUANTITY', htmlspecialchars(trim($data['quantity'] ?? '')));
        $new_product->addChild('CATEGORY', htmlspecialchars(trim($data['category'] ?? '')));
        $new_product->addChild('MANUFACTURER', htmlspecialchars(trim($data['manufacturer'] ?? '')));
        $new_product->addChild('BARCODE', htmlspecialchars(trim($data['barcode'] ?? '')));
        $new_product->addChild('WEIGHT', htmlspecialchars(trim($data['weight'] ?? '')));
        $new_product->addChild('INSTOCK', htmlspecialchars(trim($data['instock'] ?? 'Y')));
        $new_product->addChild('AVAILABILITY', htmlspecialchars(trim($data['availability'] ?? '')));

        $xmldata->asXML($this->xml_file_path);
    }
}
