<?php
declare(strict_types=1);

namespace CharlesRothDotNet\Alfred;

class TempFile {

   private string      $path;

   public function __construct(string $text) {
      $this->path = tempnam("", "");
      file_put_contents($this->path, $text);
   }

   public function getPath(): string {
      return $this->path;
   }

   public function delete(): void {
      unlink($this->path);
   }

}