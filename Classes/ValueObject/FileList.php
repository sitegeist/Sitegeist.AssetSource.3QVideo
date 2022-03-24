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
    private array $files = [];
    private int $count = 0;
    private int $position = 0;

    public function __construct(array $files)
    {
        $this->files = $files;
        $this->count = count($files);
    }

    public static function fromApiResult(array $result): self
    {
        $files = array_map(static fn(array $file) => File::fromApiResult($file), $result['Files']);
        return new self(array_values($files));
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public static function fromArray(array $files): self
    {
        return new self(array_values($files));
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
