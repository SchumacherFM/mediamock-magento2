<?php

namespace SchumacherFM\MediaMock\Catalog\Model\Product;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Image as MagentoImage;
use Magento\Store\Model\Store;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @method string getFile()
 * @method string getLabel()
 * @method string getPosition()
 */
class Image extends \Magento\Catalog\Model\Product\Image
{

    /**
     * Set filenames for base file and new file
     *
     * @param string $file
     * @return $this
     * @throws \Exception
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function setBaseFile($file)
    {
        \Zend_Debug::dump($file);
        exit;

        $this->_isBaseFilePlaceholder = false;

        if ($file && 0 !== strpos($file, '/', 0)) {
            $file = '/' . $file;
        }
        $baseDir = $this->_catalogProductMediaConfig->getBaseMediaPath();

        if ('/no_selection' == $file) {
            $file = null;
        }
// SchumacherFM: Not needed
//        if ($file) {
//            if (!$this->_fileExists($baseDir . $file) || !$this->_checkMemory($baseDir . $file)) {
//                $file = null;
//            }
//        }
        if (!$file) {
            $this->_isBaseFilePlaceholder = true;
            // check if placeholder defined in config
            $isConfigPlaceholder = $this->_scopeConfig->getValue(
                "catalog/placeholder/{$this->getDestinationSubdir()}_placeholder",
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            $configPlaceholder = '/placeholder/' . $isConfigPlaceholder;
            if (!empty($isConfigPlaceholder) && $this->_fileExists($baseDir . $configPlaceholder)) {
                $file = $configPlaceholder;
            } else {
                $this->_newFile = true;
                return $this;
            }
        }

        $baseFile = $baseDir . $file;

        if (!$file || !$this->_mediaDirectory->isFile($baseFile)) {
            throw new \Exception(__('We can\'t find the image file.'));
        }

        $this->_baseFile = $baseFile;

        // build new filename (most important params)
        $path = [
            $this->_catalogProductMediaConfig->getBaseMediaPath(),
            'cache',
            $this->_storeManager->getStore()->getId(),
            $path[] = $this->getDestinationSubdir(),
        ];
        if (!empty($this->_width) || !empty($this->_height)) {
            $path[] = "{$this->_width}x{$this->_height}";
        }

        // add misk params as a hash
        $miscParams = [
            ($this->_keepAspectRatio ? '' : 'non') . 'proportional',
            ($this->_keepFrame ? '' : 'no') . 'frame',
            ($this->_keepTransparency ? '' : 'no') . 'transparency',
            ($this->_constrainOnly ? 'do' : 'not') . 'constrainonly',
            $this->_rgbToString($this->_backgroundColor),
            'angle' . $this->_angle,
            'quality' . $this->_quality,
        ];

        // if has watermark add watermark params to hash
        if ($this->getWatermarkFile()) {
            $miscParams[] = $this->getWatermarkFile();
            $miscParams[] = $this->getWatermarkImageOpacity();
            $miscParams[] = $this->getWatermarkPosition();
            $miscParams[] = $this->getWatermarkWidth();
            $miscParams[] = $this->getWatermarkHeight();
        }

        $path[] = md5(implode('_', $miscParams));

        // append prepared filename
        $this->_newFile = implode('/', $path) . $file;
        // the $file contains heading slash

        return $this;
    }

    /**
     * First check this file on FS
     * If it doesn't exist - try to download it from DB
     *
     * @param string $filename
     * @return bool
     */
    protected function _fileExists($filename)
    {
        return false;
    }

    /**
     * Return resized product image information
     *
     * @return array
     */
    public function getResizedImageInfo()
    {
        \Zend_Debug::dump([
            'getResizedImageInfo',
            __FILE__,
            __LINE__,
            $this->_newFile
        ]);
        exit;

//        if ($this->_newFile === true) {
//            $fileInfo = getimagesize(
//                $this->_assetRepo->createAsset(
//                    "Magento_Catalog::images/product/placeholder/{$this->getDestinationSubdir()}.jpg"
//                )->getSourceFile()
//            );
//        } else {
//            $fileInfo = getimagesize($this->_mediaDirectory->getAbsolutePath($this->_newFile));
//        }
//        return $fileInfo;
        return [];
    }
}
