<?php

namespace Sitegeist\AssetSource\ThreeQVideo\ValueObject;

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
class FileList implements \Iterator, \ArrayAccess, \Countable
{
    /**
     * @var array<File>
     */
    private $files = [];

    /**
     * @var int
     */
    private $count = 0;

    /**
     * @var int
     */
    private $position = 0;

    public function __construct(File ...$files)
    {
        $this->files = $files;
        $this->count = count($files);
    }

    public static function fromApiResult(array $result): self
    {
        $files = array_map(
            function(array $file) {
                return File::tryFromApiResult($file);
            },
            $result['Files']
        );
        $files = array_filter($files);
        return new self(...$files);
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public static function fromArray(array $files): self
    {
        return new self(...$files);
    }

    public function count(): int
    {
        return $this->count;
    }

    public function current(): ?File
    {
        return $this->files[$this->position];
    }

    public function next(): void
    {
        $this->position ++;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->files[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function offsetExists($offset): bool
    {
        return \array_key_exists($offset, $this->files);
    }

    public function offsetGet($offset): ?File
    {
        return $this->files[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        throw new \BadMethodCallException('This object is immutable');
    }

    public function offsetUnset($offset): void
    {
        throw new \BadMethodCallException('This object is immutable');
    }

    /**
     * @return array<File>
     */
    public function toArray(): array
    {
        return array_values($this->files);
    }



}
