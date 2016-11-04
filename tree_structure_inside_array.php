<? php
    // Get tree structure inside array
    $res = $this->build_tree($result_set);
    /**
     * Built Tree strucure inside array
     * @param array $elements
     * @return string
     */
    function build_tree(&$elements, $parentId = 0) {
        $branch = array();
        foreach ($elements as $k => $element) {
            if ($element['parent_position'] == $parentId) {
                $children = $this->build_tree($elements, $element['page_id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element['page_id']] = $element;
            }
        }
        return $branch;
    }
