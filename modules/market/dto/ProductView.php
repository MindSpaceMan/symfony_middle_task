<?php // modules/market/dto/ProductView.php
namespace Market\dto;

final class ProductView
{
    public int $id;
    public string $name;
    public string $description;
    public string $category;
    /** @var string[]|null */
    public ?array $images = null;       // НОВОЕ
    public ?bool $is_favorite = null;   // НОВОЕ (null для гостя)
    public ?string $image_url = null;   // СТАРОЕ — остаётся!
}