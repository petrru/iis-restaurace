<?php

/**
 * Class Mapper
 * Převádí URL na instanci třídy Page
 */
class Mapper
{
    /// Regulární výrazy
    private $map = [
        ["^$", StaticPage::class],
        ["^about$", StaticPage::class],
        ["^contact$", StaticPage::class],
        ["^menu$", MenuPage::class],
        ["^login$", LoginPage::class],
        ["^logout$", LogoutPage::class],
        ["^reservation$", ReservationPage::class],
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
        ["^manage/reservations/(([0-9]{4}-[0-9]{2})|current)$",
            ReservationsPage::class],
        ["^manage/reservations/([0-9]+|new)$", ReservationAdminPage::class],
        ["^manage/reservations/([0-9]+)/delete$", DeleteReservationPage::class],
        ["^manage/orders$", OrdersPage::class],
        ["^manage/orders/all$", AllOrdersPage::class],
        ["^manage/orders/new/([0-9]+)/?([0-9]*)$", NewOrderPage::class],
        ["^manage/orders/([0-9]+)$", OrderPage::class],
        ["^manage/orders/([0-9]+)/item$", OrderAddItemPage::class],
        ["^manage/orders/([0-9]+)/del-item/([0-9]+)$",
            DeleteOrderedItemPage::class],
        ["^manage/orders/([0-9]+)/paid/[01]$", OrderItemPaidPage::class],
        ["^manage/change-password$", ChangePasswordPage::class],
        [".*", NotFoundPage::class]
    ];

    /** @var Page */
    private $page;

    private static $root_url;

    public function __construct()
    {
        self::$root_url = trim(file_get_contents("inc/root"));
    }

    /**
     * Vytvoří instanci třídy Page dle zadané URL adresy
     * @param $url string URL stránky
     * @return Page
     */
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

    /**
     * Vrátí absolutní URL adresu stránky
     * @param $path string Lokální URL (vzhledem ke kořeni webu)
     * @return string Absolutní URL
     */
    public static function url($path) {
        return self::$root_url . $path;
    }

    /**
     * Vrátí absolutní URL adresu kořenu webu
     */
    public static function get_root_url() {
        echo self::$root_url;
    }
}