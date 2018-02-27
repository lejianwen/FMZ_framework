<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/27
 * Time: 10:24
 */

namespace app\controllers\admin\html;

/**
 * Class SearchForm
 * @method $this like($label, $attr = '', $type = 'text')
 * @method $this eq($label, $attr = '', $type = 'text')
 * @method $this noeq($label, $attr = '', $type = 'text')
 * @method $this gt($label, $attr = '', $type = 'text')
 * @method $this egt($label, $attr = '', $type = 'text')
 * @method $this lt($label, $attr = '', $type = 'text')
 * @method $this elt($label, $attr = '', $type = 'text')
 * @package app\controllers\admin\html
 */
class SearchForm extends Form
{
    protected $like = [];
    protected $eq = [];
    protected $noeq = [];
    protected $egt = [];
    protected $gt = [];
    protected $lt = [];
    protected $elt = [];
    protected $query_relation = [
        'like' => 'like',
        'eq'   => '=',
        'noeq' => '!=',
        'egt'  => '>=',
        'gt'   => '>',
        'lt'   => '<',
        'elt'  => '<=',
    ];

    public function __construct()
    {

    }

    public function __call($func, $params)
    {
        $keys = array_keys($this->query_relation);
        if (in_array($func, $keys)) {
            if ($params[0] instanceof \Closure) {
                $input = ($params[0])($this);
            } else {
                $params[0] = $params[0] . ' (' . $func . ')';
                $method = isset($params[2]) ? $params[2] : 'text';
                unset($params[2]);
                $input = $this->$method(...$params);
            }
            ($this->$func)[$input->attr()] = $input;
            return $input;
        } else {
            return parent::__call($func, $params);
        }
    }

    /**
     * 构建query
     * @param $query
     * @param array $post_search
     * @return mixed
     * @author Lejianwen
     */
    public function buildQuery($query, $post_search = [])
    {
        if (empty($post_search)) {
            return $query;
        }

        foreach ($this->query_relation as $key => $value) {
            if (!empty($this->$key)) {
                $attrs = array_keys($this->$key);
                foreach ($attrs as $attr) {
                    //null和空字符串不筛选，0可以筛选
                    if (isset($post_search[$attr])
                        && $post_search[$attr] !== null
                        && $post_search[$attr] !== ''
                    ) {
                        if ($value == 'like') {
                            $query->where($attr, $value, "%{$post_search[$attr]}%");
                        } else {
                            $query->where($attr, $value, $post_search[$attr]);
                        }
                    }
                }
            }
        }
        return $query;
    }

    public function isEmpty()
    {
        return empty($this->inputs);
    }
}