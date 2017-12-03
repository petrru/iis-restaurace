<?php

class Mapper
{
    private $map = [
        ["^$", StaticPage::class],
        ["^about$", StaticPage::class],
        ["^contact$", StaticPage::class],
        ["^menu$", MenuPage::class],
        ["^login$", LoginPage::class],
        ["^logout$", LogoutPage::class],
        ["^reservation$", ReservationPage::class],
        ["^new-account$", RegisterPage::class],
        ["^manage$", AdminHomePage::class],
        ["^manage/employees$", EmployeesPage::class],
        ["^manage/employees/([0-9]+|new)$", EmployeePage::class],
        ["^manage/employees/([0-9]+)/delete$", DeleteEmployeePage::class],
        ["^manage/menu$", ItemsPage::class],
        ["^manage/menu/([0-9]+|new)$", ItemPage::class],
        ["^manage/menu/([0-9]+)/delete$", DeleteItemPage::class],
        ["^manage/menu/([0-9]+)/save-ingredients", SaveItemPage::class],
        ["^manage/other$", OtherPage::class],
        ["^manage/rooms$", RoomsPage::class],
        ["^manage/rooms/([0-9]+|new)$", RoomPage::class],
        ["^manage/rooms/([0-9]+)/delete$", DeleteRoomPage::class],
        ["^manage/ingredients$", IngredientsPage::class],
        ["^manage/ingredients/([0-9]+|new)$", IngredientPage::class],
        ["^manage/ingredients/([0-9]+)/delete$", DeleteIngredientPage::class],
        ["^manage/categories$", CategoriesPage::class],
        ["^manage/categories/([0-9]+|new)$", CategoryPage::class],
        ["^manage/categories/([0-9]+)/delete$", DeleteCategoryPage::class],
        [".*", NotFoundPage::class]
    ];

    /** @var Page */
    private $page;

    private static $root_url;

    public function __construct()
    {
        self::$root_url = trim(file_get_contents("inc/root"));
    }

    public function create_page($url) {
        $url = rtrim($url, '/');
        foreach ($this->map as $page_arr) {
            list($regex, $cls) = $page_arr;
            if (!preg_match(":" . $regex . ":", $url))
                continue;

            $this->page = new $cls;
            $this->page->set_url($url);
            return $this->page;
        }
        return new NotFoundPage();
    }

    public static function url($path) {
        return self::$root_url . $path;
    }

    public static function get_root_url() {
        echo self::$root_url;
    }
}