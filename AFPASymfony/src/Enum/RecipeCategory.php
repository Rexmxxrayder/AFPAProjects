<?php
namespace App\Enum;
enum RecipeCategory: string
{
    case Appetizer = "Appetizer";
    case MainCourse = "Main Course";
    case Dessert = "Dessert";
    case Beverage = "Beverage";
    case Other = "Other";
}
?>