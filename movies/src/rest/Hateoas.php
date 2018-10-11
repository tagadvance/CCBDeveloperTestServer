<?php
namespace rest;

/**
 * Hypermedia as the Engine of Application State.
 *
 * This class allows our little API to be explorable by providing consistent structure for returned data.
 * We can easily expand it to support pagination.
 */
class Hateoas
{

    private $data = [];

    function __construct()
    {}

    /**
     * Add a hyperlink to this entity.
     *
     * @param string $href
     * @param string $rel
     * @return self
     */
    function addLink(string $href, string $rel = null): self
    {
        if (! isset($this->data['links'])) {
            $this->data['links'] = [];
        }
        
        $link = [
            'href' => $href
        ];
        if (isset($rel)) {
            $link['rel'] = $rel;
        }
        array_push($this->data['links'], $link);
        
        return $this;
    }

    /**
     * Add a text message to this entity, e.g.
     * a welcome message or hint.
     *
     * @param string $key
     * @param string $value
     * @return self
     */
    function addText(string $key, string $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * Add a named collection to this entity, e.g.
     * example data.
     *
     * @param string $name
     * @param array $collection
     */
    function addNamedCollection(string $name, array $collection)
    {
        $this->data[$name] = $collection;
    }

    /**
     * Export entity in standardized form.
     *
     * @return array
     */
    function export(): array
    {
        return $this->data;
    }

    /**
     * Export entity in standardized form.
     *
     * @param array $collection
     * @return array
     */
    function exportWithCollection(array $collection): array
    {
        $this->data['collection'] = $collection;
        return $this->data;
    }

    /**
     * Export entity in standardized form.
     *
     * @param array $collection
     * @return array
     */
    function exportWithItem($item): array
    {
        $this->data['item'] = $item;
        return $this->data;
    }

    static function exportMessage($message): array
    {
        return [
            'message' => $message
        ];
    }
}