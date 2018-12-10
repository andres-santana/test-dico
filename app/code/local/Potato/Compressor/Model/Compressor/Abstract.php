<?php

abstract class Potato_Compressor_Model_Compressor_Abstract
{
    const MERGED_FOLDER          = 'merged';
    const PREPARED_FOLDER        = 'prepared';
    const MERGED_HANDLES_FOLDER  = 'merged_handles';

    protected $_storeId          = null;
    protected $_handleCollection = array();
    protected $_mergedFiles      = array();
    protected $_infoFilename     = '.info';

    abstract protected function _compression($content);
    abstract public function canGzip();
    abstract public function canCompression();
    abstract public function canMerge();
    abstract protected function _getElementHtml();
    abstract protected function _getElementConditionHtml();
    abstract protected function _getFileExtension();
    abstract protected function _getFolderName();

    /**
     * load merged files by storeId
     * @param $storeId
     *
     * @return $this
     */
    public function loadByStoreId($storeId)
    {
        $this->setStoreId($storeId);
        if (is_dir($this->_getMergedFilesPath())) {
            $findedDirectories = array_diff(scandir($this->_getMergedFilesPath()), array('..', '.'));
            foreach ($findedDirectories as $_directory) {
                $dirPath = $this->_getMergedFilesPath() . DS . $_directory;
                $this->_mergedFiles[$_directory] = array(
                    'if'     => $this->_getDirFilesAsArray($dirPath . DS . 'if', true, false),
                    'params' => $this->_getDirFilesAsArray($dirPath . DS . 'params', true, false),
                    'common' => $this->_getDirFilesAsArray($dirPath . DS . 'common', true, false)
                );
            }
        }
        return $this;
    }

    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    /**
     * load prepared handle files
     * @param array $handles
     *
     * @return string
     */
    public function loadPreparedHandles(array $handles)
    {
        $preparedFiles = '';
        $handles = array_merge(array('common'), $this->_prepareDefaultHandle($handles));
        if ($this->inSingleFile()) {
            return $this->_getSkinElementSingle($handles);
        }
        if (is_dir($this->_getPreparedFilesPath())) {
            foreach ($handles as $handleName) {
                $dirPath = $this->_getPreparedFilesPath() . DS . $handleName;
                $_handleFiles = array(
                    'if'     => $this->_getDirFilesAsArray($dirPath . DS . 'if', false, true),
                    'params' => $this->_getDirFilesAsArray($dirPath . DS . 'params', false, true),
                    'common' => $this->_getDirFilesAsArray($dirPath . DS . 'common', false, true)
                );
                $_handleElements = $this->_getSkinElement($_handleFiles);
                if ($_handleElements) {
                    $preparedFiles.= $_handleElements;
                }
            }
        }
        return $preparedFiles;
    }

    /**
     * prepare handles files for merge in single file
     * @param array $handles
     *
     * @return string
     */
    protected function _getSkinElementSingle(array $handles)
    {
        $preparedFiles = '';
        if (is_dir($this->_getPreparedFilesPath()) && is_dir($this->_getMergedHandlesPath())) {
            $mergedFilesName = md5(implode('&', $this->_getHandleFileList($handles)));
            $dirPath = $this->_getMergedHandlesPath() . DS . $mergedFilesName;
            if (!is_dir($dirPath)) {
                $this->_prepareFolder($dirPath);
                $_handleFiles = array(
                    'if'     => array(),
                    'params' => array(),
                    'common' => array()
                );
                foreach ($handles as $handleName) {
                    $preparedFilesPath = $this->_getPreparedFilesPath() . DS . $handleName;
                    $_result = $this->_getDirFilesAsArray($preparedFilesPath . DS . 'if', true, false);
                    if (!empty($_result)) {
                        $_handleFiles['if'] = array_merge($_handleFiles['if'], $_result);
                    }
                    $_result = $this->_getDirFilesAsArray($preparedFilesPath . DS . 'params', true, false);
                    if (!empty($_result)) {
                        $_handleFiles['params'] = array_merge($_handleFiles['params'], $_result);
                    }
                    $_result = $this->_getDirFilesAsArray($preparedFilesPath . DS . 'common', true, false);
                    if (!empty($_result)) {
                        $_handleFiles['common'] = array_merge($_handleFiles['common'], $_result);
                    }
                }
                $this->_mergeHandlesInSingle(array_filter($_handleFiles), $mergedFilesName, $dirPath);
            }
            $_handleFiles = array(
                'if'     => $this->_getDirFilesAsArray($dirPath . DS . 'if', false, true),
                'params' => $this->_getDirFilesAsArray($dirPath . DS . 'params', false, true),
                'common' => $this->_getDirFilesAsArray($dirPath . DS . 'common', false, true)
            );
            $_handleElements = $this->_getSkinElement($_handleFiles);
            if ($_handleElements) {
                $preparedFiles.= $_handleElements;
            }
        }
        return $preparedFiles;
    }

    /**
     * Merge prepared handle files in single file
     * @param array $_handleFiles
     * @param       $mergedFilesName
     * @param       $dirPath
     *
     * @return $this
     */
    protected function _mergeHandlesInSingle(array $_handleFiles, $mergedFilesName, $dirPath)
    {
        foreach ($_handleFiles as $key => $_files) {
            $_folderPath = $dirPath . DS . $key;
            $this->_prepareFolder($_folderPath);
            $filesList = array();
            if ($key == 'common') {
                $filesList = '';
            }
            foreach ($_files as $_fileInfo) {
                if (array_key_exists('info', $_fileInfo)) {
                    if (!isset($filesList) || !array_key_exists(md5($_fileInfo['info']), $filesList)) {
                        $filesList[md5($_fileInfo['info'])] = array(
                            'content' => '',
                            'info'    => ''
                        );
                    }
                    $filesList[md5($_fileInfo['info'])] = array(
                        'content' => $filesList[md5($_fileInfo['info'])]['content'] . $_fileInfo['content'] . ';',
                        'info'    => $_fileInfo['info']
                    );
                    continue;
                }
                $filesList .= $_fileInfo['content'];
            }

            $_md5FileName = md5(time() . $_folderPath . DS . $mergedFilesName) . '.' . $this->_getFileExtension();
            if (is_array($filesList)) {
                foreach ($filesList as $_handleFiles) {
                    $_md5FileName = md5(time() . $_folderPath . DS . $mergedFilesName
                            . $_handleFiles['info']) . '.' . $this->_getFileExtension()
                    ;
                    $this->_filePutContent($_md5FileName . $this->_infoFilename, $_handleFiles['info'], $_folderPath);
                    $this->_filePutContent($_md5FileName, $_handleFiles['content'], $_folderPath);
                }
                continue;
            }
            $this->_filePutContent($_md5FileName, $filesList, $_folderPath);
        }
        return $this;
    }

    /**
     * prepare files for default handle
     * @param array $handles
     *
     * @return array
     */
    protected function _prepareDefaultHandle(array $handles)
    {
        if (in_array('default', $handles)) {
            ksort($handles);
            $_defaultHandleName = $this->_getDefaultHandle($handles, 'default');
            if ($_defaultHandleName) {
                unset($handles[array_search('default', $handles)]);
                $handles = array_merge(array($_defaultHandleName), $handles);
            }
        }
        return $handles;
    }

    /**
     * get default handle for current handles
     * for handle with removed layout tags will be unique default handle file
     * @param array  $handles
     * @param string $preparedHandleName
     *
     * @return bool|string
     */
    protected function _getDefaultHandle(array $handles, $preparedHandleName = 'default')
    {
        $_findedHandle = false;
        foreach ($handles as $handleName) {
            if ($handleName === $preparedHandleName) {
                continue;
            }

            if (is_dir($this->_getPreparedFilesPath() . DS . $preparedHandleName . '&' . $handleName)) {
                $_findedHandle = $this->_getDefaultHandle($handles, $preparedHandleName . '&' . $handleName);
                if (!$_findedHandle) {
                    $_findedHandle = $preparedHandleName . '&' . $handleName;
                }
            }
        }
        return $_findedHandle;
    }

    /**
     * get files list as array
     * array(content, file_path, filename, info)
     * @param      $dirPath
     * @param bool $content
     * @param bool $filePath
     *
     * @return array|string
     */
    protected function _getDirFilesAsArray($dirPath, $content = true, $filePath = false)
    {
        if (!is_dir($dirPath)) {
            return '';
        }
        $findedFiles = scandir($dirPath);
        $result = array();
        foreach ($findedFiles as $_fileName) {
            $_keys = array();
            if (pathinfo($dirPath . DS . $_fileName, PATHINFO_EXTENSION) == $this->_getFileExtension()) {
                if ($content) {
                    $_keys['content'] = file_get_contents($dirPath . DS . $_fileName);
                }
                if ($filePath) {
                    $_keys['file_path'] = $dirPath . DS . $_fileName;
                }
                $_keys['filename'] = $_fileName;
                if (file_exists($dirPath . DS . $_fileName . $this->_infoFilename)) {
                    $_keys['info'] = file_get_contents($dirPath . DS . $_fileName . $this->_infoFilename);
                }
                $result[] = $_keys;
            }
        }
        return $result;
    }

    public function isLoaded()
    {
        if ($this->_mergedFiles) {
            return true;
        }
        return false;
    }

    public function process()
    {
        try {
            if (null !== $this->_storeId) {
                $this
                    ->_checkAndPrepareFolders()
                    ->_processMerge()
                    ->_removeFolder($this->_getPreparedFilesPath())
                    ->_removeFolder($this->_getMergedHandlesPath())
                    ->_prepareFilesAndSave()
                ;
            }
        } catch (Exception $e) {
            Mage::logException($e->getMessage());
        }
        return $this;
    }

    protected function _checkAndPrepareFolders()
    {
        $this
            ->_prepareFolder(Mage::helper('po_compressor')->getRootCachePath())
            ->_prepareFolder(Mage::helper('po_compressor')->getRootCachePath() . DS . $this->_storeId)
            ->_prepareFolder($this->_getFullPath())
            ->_prepareFolder($this->_getMergedFilesPath())
            ->_prepareFolder($this->_getPreparedFilesPath())
            ->_prepareFolder($this->_getMergedHandlesPath())
        ;
        return $this;
    }

    protected function _prepareFolder($folderPath)
    {
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777);
        }
        return $this;
    }

    protected function _processMerge()
    {
        if ($this->canMerge()) {
            if ($this->_handleCollection) {
                foreach ($this->_handleCollection as $_handleName => $_handleItems) {
                    $this
                        ->_prepareFolder($this->_getMergedFilesPath() . DS . $_handleName)
                        ->_mergeHandleConditions($_handleItems, $_handleName)
                    ;
                }
            }
        }
        return $this;
    }

    protected function _mergeHandleConditions(array $handleItems, $_handleName)
    {
        foreach ($handleItems as $key => $_condition) {
            $_folderPath = $this->_getMergedFilesPath() . DS . $_handleName . DS . $key;
            $this->_prepareFolder($_folderPath);
            foreach ($_condition as $index => $_params) {
                if (!array_key_exists('files', $_params)){
                    continue;
                }
                $mergedContent = $this->_merge($_params['files']);
                if (trim($mergedContent) == '') {
                    continue;
                }
                $_keys = array();
                $_fileName = $key . '.' . $this->_getFileExtension();
                $_md5FileName = md5(time() . $_folderPath . DS . $_fileName) . '.' . $this->_getFileExtension();
                if (array_key_exists('condition', $_params)) {
                    $_md5FileName = md5(time() . $_folderPath . DS . $_fileName . '_' . $index)
                        . '.' . $this->_getFileExtension()
                    ;
                    $this->_filePutContent($_md5FileName . $this->_infoFilename, $_params['condition'], $_folderPath);
                    $_keys = array('info' => $_params['condition']);
                }
                $_keys = array_merge($_keys, array('filename' => $_md5FileName, 'content' => $mergedContent));
                $this->_mergedFiles[$_handleName][$key][] = $_keys;
                $this->_filePutContent($_md5FileName, $mergedContent, $_folderPath);
            }
        }
        return $this;
    }

    protected function _removeFolder($dirPath)
    {
        $findedFiles = array_diff(scandir($dirPath), array('..', '.'));
        foreach ($findedFiles as $file) {
            if (is_dir($dirPath . DS . $file)) {
                $this->_removeFolder($dirPath . DS . $file);
                rmdir($dirPath . DS . $file);
                continue;
            }
            unlink($dirPath . DS . $file);
        }
        return $this;
    }

    protected function _prepareFilesAndSave()
    {
        if ($this->_mergedFiles) {
            foreach ($this->_mergedFiles as $_handleName => $_handleItems) {
                $this->_prepareHandleConditions($_handleItems, $_handleName);
            }
        }
        if ($this->canGzip()) {
            $this->_createHtaccessFile();
        }
        return $this;
    }

    protected function _createHtaccessFile()
    {
        $content = "<ifmodule mod_deflate.c>\n"
            . "AddOutputFilterByType DEFLATE application/javascript\n"
            . "AddOutputFilterByType DEFLATE application/x-javascript\n"
            . "AddOutputFilterByType DEFLATE text/css\n"
            . "SetOutputFilter DEFLAT\n"
            . "</ifmodule>\n"
        ;
        $this->_filePutContent('.htaccess', $content, $this->_getFullPath());
        return $this;
    }

    protected function _prepareHandleConditions($_handleItems, $_handleName)
    {
        $_handleFolderPath = $this->_getPreparedFilesPath() . DS . $_handleName;
        $this->_prepareFolder($_handleFolderPath);

        foreach ($_handleItems as $key => $_conditions) {
            $this->_prepareFolder($_handleFolderPath . DS . $key);

            foreach ($_conditions as $_params) {
                $filename = $_params['filename'];
                $content = $_params['content'];

                if ($this->canCompression() && trim($content) != '') {
                    $content = $this->_compression($content);
                }

                $this->_filePutContent($filename, $content, $_handleFolderPath . DS . $key);

                if (array_key_exists('info', $_params)) {
                    $this->_filePutContent($filename . $this->_infoFilename,
                        $_params['info'],
                        $_handleFolderPath . DS . $key
                    );
                }
            }
        }
        return $this;
    }

    protected function _merge(array $files)
    {
        $mergedContent = '';
        foreach ($files as $file) {
            if (file_exists($file) && pathinfo($file, PATHINFO_EXTENSION) == $this->_getFileExtension() && $this->isAllowToCompress($file)) {
                $content = $this->_beforeMerge($file);
                $mergedContent.= $content;
            }
        }
        return $mergedContent;
    }

    protected function _beforeMerge($file)
    {
        return file_get_contents($file);
    }

    public function setHandleCollection(array $files)
    {
        $this->_handleCollection = $files;
        return $this;
    }

    protected function _filePutContent($filename, $content, $path)
    {
        file_put_contents($path . DS . $filename, $content);
        return $this;
    }

    protected function _getFullPath()
    {
        return Mage::helper('po_compressor')->getRootCachePath() . DS . $this->_storeId . DS . $this->_getFolderName();
    }

    protected function _getMergedFilesPath()
    {
        return $this->_getFullPath() . DS . self::MERGED_FOLDER;
    }

    protected function _getPreparedFilesPath()
    {
        return $this->_getFullPath() . DS . self::PREPARED_FOLDER;
    }

    protected function _getMergedHandlesPath()
    {
        return $this->_getFullPath() . DS . self::MERGED_HANDLES_FOLDER;
    }

    protected function _getSkinElement(array $handleFiles)
    {
        $_result = '';
        foreach ($handleFiles as $key => $condition) {
            if (!is_array($condition)) {
                continue;
            }

            foreach ($condition as $params) {
                $filePath = str_replace(Mage::helper('po_compressor')->getRootCachePath(),
                    Mage::helper('po_compressor')->getRootCacheUrl()
                    , $params['file_path']
                );

                $filePath = str_replace('\\', '/', $filePath);
                if (array_key_exists('info', $params)) {
                    if ($key === 'params') {
                        $_result .= sprintf($this->_getElementConditionHtml() . "\n", $params['info'], $filePath);
                    }
                    if ($key === 'if') {
                        $_result .= '<!--[if ' . $params['info'] . ']>' . "\n";
                        $_result .= sprintf($this->_getElementHtml() . "\n", $filePath);
                        $_result .= '<![endif]-->' . "\n";
                    }
                    continue;
                }
                $_result .= sprintf($this->_getElementHtml() . "\n", $filePath);
            }
        }
        return $_result;
    }

    protected function _getHandleFileList($handles)
    {
        $layoutModel = Mage::getModel('po_compressor/layout')->loadByStoreId($this->_storeId);
        $_preparedMergedFiles = $layoutModel->getHandlesFilesList($handles);
        $fileList = array();
        foreach ($_preparedMergedFiles as $filePath) {
            if (pathinfo($filePath, PATHINFO_EXTENSION) == $this->_getFileExtension()) {
                $fileList[] = $filePath;
            }
        }
        return $fileList;
    }
}