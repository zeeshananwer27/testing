<?php

namespace Themes\Storefront\Http\ViewComposers;

use Illuminate\Support\Collection;
use Modules\Product\RecentlyViewed;
use Modules\Slider\Entities\Slider;
use Themes\Storefront\Admin\Banner;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\DB;
class HomePageComposer
{
    /**
     * The recently viewed instance.
     *
     * @var \Modules\Product\RecentlyViewed
     */
    private $recentlyViewed;

    /**
     * Create a new instance.
     *
     * @param \Modules\Product\RecentlyViewed $recentlyViewed
     */
    public function __construct(RecentlyViewed $recentlyViewed)
    {
        $this->recentlyViewed = $recentlyViewed;
    }

    /**
     * Bind data to the view.
     *
     * @param \Illuminate\View\View $view
     * @return void
     */
    public function compose($view)
    {
        $view->with([
            'slider' => Slider::findWithSlides(setting('storefront_slider')),
            'sliderBanners' => Banner::allForSliderBanners(),
            'bannerSectionOneBanners' => $this->getBannerSectionOneBanners(),
            'features' => $this->getFeatures(),
            'carouselProducts' => $this->getCarouselProducts(),
            'recentProducts' => $this->getRecentProducts(),
            'bannerSectionTwoBanner' => $this->getBannerSectionTwoBanner(),
            'threeColumnCarouselProducts' => $this->getThreeColumnCarouselProducts(),
            'landscapeProducts' => $this->getLandscapeProducts(),
            'bannerSectionThreeBanners' => $this->getBannerSectionThreeBanners(),
            'tabProducts' => $this->getTabProducts(),
            'twoColumnCarouselProducts' => $this->getTwoColumnCarouselProducts(),
            'recentlyViewedProducts' => $this->getRecentlyViewedProducts(),
        ]);
    }

    private function getBannerSectionOneBanners()
    {
        if (setting('storefront_banner_section_1_enabled')) {
            return Banner::allForSectionOne();
        }
    }

    private function getFeatures()
    {
        if (! setting('storefront_features_section_enabled')) {
            return collect();
        }

        return Collection::times(4, function ($number) {
            return $this->getFeatureFor($number);
        })->filter(function ($feature) {
            return ! is_null($feature['icon']);
        });
    }

    private function getFeatureFor($number)
    {
        return [
            'icon' => setting("storefront_feature_{$number}_icon"),
            'title' => setting("storefront_feature_{$number}_title"),
            'subtitle' => setting("storefront_feature_{$number}_subtitle"),
        ];
    }

    private function getRecentProducts()
    {
        if (! setting('storefront_recent_products_section_enabled')) {
            return collect();
        }
        

    // $events = Product::forCard()
    //         ->latest()
    //         ->take(setting('storefront_recent_products_section_total_products', 10))
    //         ->orderBy('created_at', 'DESC') 
    //         ->select('*')
    //         ->get();
    
    $info =  unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));
        if(isset($info['geoplugin_latitude']) && isset($info['geoplugin_longitude'])){
            $current_lat = $info['geoplugin_latitude'];
            $current_lng = $info['geoplugin_longitude'];   
        }
     $events = Product::forCard()
            // ->join('product_translations', 'product_translations.product_id', '=', 'products.id')
            ->oldest('start_event')
            ->take(setting('storefront_recent_products_section_total_products', 10))
            // ->where('products.is_active', 1)
            // ->orderBy(DB::raw('start_event'), 'desc')
            ->select('*')
            // ->select('*', DB::raw("sqrt(abs($current_lng - lng) * (cos(PI() * lat / 180.0) * (40000 / 360)) * abs($current_lng - lng) * (cos(PI() * lat / 180.0) * (40000 / 360)) + abs($current_lat - lat) * (40000 / 360) * abs($current_lat - lat) * (40000 / 360)) as distance"))
            ->get();
    
   
    $event_data = DB::table('product_translations')->get();
    
        foreach($events as $event ){
        
            foreach($event_data as $et_data){
                if($event->id == $et_data->product_id){
                    $event->address = $et_data->address; 
                    $event->lat = $et_data->lat; 
                    $event->lng = $et_data->lng; 
                } 
            }
        }
    // echo "<pre>";print_r($events->toArray()); exit('all');   
        return $events; 
    }
    

    // private function getRecentProducts()
    // {
    //     if (! setting('storefront_recent_products_section_enabled')) {
    //         return collect();
    //     }

    // $current_lat='';
    // $current_lng='';
    // $info =  unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));
    // if(isset($info['geoplugin_latitude']) && isset($info['geoplugin_longitude'])){
    //     $current_lat = $info['geoplugin_latitude'];
    //     $current_lng = $info['geoplugin_longitude'];   
    // }
    // else{
    //     $current_lat='31.4888';
    //     $current_lng='74.3686';    
    // }

    //  $events = Product::forCard()
    //         ->join('product_translations', 'product_translations.product_id', '=', 'products.id')
    //         ->latest()
    //         ->take(setting('storefront_recent_products_section_total_products', 50))
    //         ->where('deleted_at', NULL)
    //         ->orderBy('distance', 'ASC') 
    //         ->select('*', 
    //           DB::raw("sqrt(abs($current_lng - lng) * (cos(PI() * lat / 180.0) * (40000 / 360)) * abs($current_lng - lng) * (cos(PI() * lat / 180.0) * (40000 / 360)) + abs($current_lat - lat) * (40000 / 360) * abs($current_lat - lat) * (40000 / 360)) as distance"))
            
    //         ->get();
    // // echo "<pre>"; print_r($events); exit('disss'); 


    //     return $events; 
    // }


    private function getBannerSectionTwoBanner()
    {
        if (setting('storefront_banner_section_2_enabled')) {
            return Banner::findByName('storefront_banner_section_2_banner');
        }
    }

    private function getCarouselProducts()
    {
        if (! setting('storefront_product_carousel_section_enabled')) {
            return collect();
        }

        return $this->getProductsForCardByIds(setting('storefront_product_carousel_section_products'));
    }

    private function getBannerSectionThreeBanners()
    {
        if (setting('storefront_banner_section_3_enabled')) {
            return Banner::allForSectionThree();
        }
    }

    private function getTabProducts()
    {
        if (! setting('storefront_product_tabs_section_enabled')) {
            return collect();
        }

        return [
            'tab_1' => $this->getProductsForCardByIds(setting('storefront_product_tabs_section_tab_1_products')),
            'tab_2' => $this->getProductsForCardByIds(setting('storefront_product_tabs_section_tab_2_products')),
            'tab_3' => $this->getProductsForCardByIds(setting('storefront_product_tabs_section_tab_3_products')),
            'tab_4' => $this->getProductsForCardByIds(setting('storefront_product_tabs_section_tab_4_products')),
        ];
    }

    private function getThreeColumnCarouselProducts()
    {
        if (! setting('storefront_three_column_vertical_product_carousel_section_enabled')) {
            return collect();
        }

        return [
            'column_1' => $this->getProductsForCardByIds(setting('storefront_three_column_vertical_product_carousel_section_column_1_products')),
            'column_2' => $this->getProductsForCardByIds(setting('storefront_three_column_vertical_product_carousel_section_column_2_products')),
            'column_3' => $this->getProductsForCardByIds(setting('storefront_three_column_vertical_product_carousel_section_column_3_products')),
        ];
    }

    private function getLandscapeProducts()
    {
        if (! setting('storefront_landscape_products_section_enabled')) {
            return collect();
        }

        return $this->getProductsForCardByIds(setting('storefront_landscape_products_section_products'));
    }

    private function getTwoColumnCarouselProducts()
    {
        if (! setting('storefront_two_column_product_carousel_section_enabled')) {
            return collect();
        }

        return [
            'column_1' => $this->getProductsForCardByIds(setting('storefront_two_column_product_carousel_section_column_1_products')),
            'column_2' => $this->getProductsForCardByIds(setting('storefront_two_column_product_carousel_section_column_2_products')),
        ];
    }

    private function getProductsForCardByIds($ids)
    {
        return Product::forCard()
            ->whereIn('id', $ids ?: [])
            ->get();
    }

    private function getRecentlyViewedProducts()
    {
        if (! setting('storefront_recently_viewed_section_enabled')) {
            return collect();
        }

        return collect($this->recentlyViewed->products())
            ->reverse()
            ->take(setting('storefront_recently_viewed_section_total_products', 5));
    }
}
