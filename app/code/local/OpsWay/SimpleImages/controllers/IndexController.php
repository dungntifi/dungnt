<?php

class OpsWay_SimpleImages_IndexController extends Mage_Adminhtml_Controller_Action {

	public function indexAction(){
		$productId = $this->getRequest()->getParam('productId');
		$simpleId = $this->getRequest()->getParam('simpleId');
		
		if(isset($simpleId) && isset($productId)) {

			//data preset
			$product = Mage::getModel('catalog/product')->load($productId);   

			$productAttributes = Mage::getSingleton('eav/config')->getAttribute('catalog_product', 'color');
			if ($productAttributes->usesSource()) {
			    $options = $productAttributes->getSource()->getAllOptions(false);
			}

			$gallery = $product->getMediaGalleryImages();
			$galleryWidth = count($gallery) * 160; //gallery-item width + margin

			//data load
			$imagesGallery = '<div class="images-gallery" style="width: ' . $galleryWidth . 'px;">';

			$i = 0;
			foreach ($gallery as $key => $image) {
				$checked = ($i == 0) ? 'checked' : '';
				$imagesGallery .= '<div class="gallery-item">
					<img src="' . $image->getUrl() . '" />
					<input type="checkbox" style="display:none;" name="simpleimages_gallery[' . $simpleId . '][' . $key . ']" value="' . $image->getFile() . '" />
					<fieldset>
						<legend>Set image type</legend>
						<input type="radio" ' . $checked . ' value="' . $key . '" name="simpleimages_type[' . $simpleId . '][image]"> Base image<br>
						<input type="radio" ' . $checked . ' value="' . $key . '" name="simpleimages_type[' . $simpleId . '][small_image]"> Small image<br>
						<input type="radio" ' . $checked . ' value="' . $key . '" name="simpleimages_type[' . $simpleId . '][thumbnail]"> Thumbnail
					</fieldset>
				</div>';
				$i++;
			}
			$imagesGallery .= '</div>';

			$colorOptions = '';
			foreach ($options as $option) {
				$colorOptions .= '<option value="' . $option['value'] . '">' . $option['label'] . '</option>';
			}

			$template = '<tr class="row" id="simpleimages-' . $simpleId . '"><td class="selected-simple"></td><td class="selected-color input-field"><select name="simpleimages_color[' . $simpleId . ']" class="required-entry select">' . $colorOptions . '</select></td><td class="selected-images input-field">' . $imagesGallery . '</td><td class="selected-remove"><p>Remove this row, if you don`t want<br>to change this simple product</p><a href="#" class="button">Remove</a></td></tr>';
		}

		$this->getResponse()->setHeader('Content-type', 'application/json', true) ;
	    return $this->getResponse()->setBody($template);
	}
}