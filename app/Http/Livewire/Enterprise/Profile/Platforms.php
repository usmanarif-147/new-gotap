<?php

namespace App\Http\Livewire\Enterprise\Profile;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Platform;

class Platforms extends Component
{
    public $profile_id;

    public $tab_change;

    public $path, $label, $direct, $title, $input;
    public $platformId;

    public $c_modal_heading = '', $c_modal_body = '', $c_modal_btn_text = '', $c_modal_btn_color = '', $c_modal_method = '';
    public $isEditMode = false;

    public $searchTerm = '';
    // public $categories = [];
    // public $sortedPlatforms = [];

    protected $listeners = ['refresh-platforms' => 'search'];

    public function mount($id, $tab)
    {
        $this->profile_id = $id;
        $this->tab_change = $tab;
    }

    public function updateOrder($order)
    {
        $orderList = $order;
        $id = array_column($orderList, 'value');
        array_multisort($id, SORT_ASC, $orderList);
        foreach ($orderList as $platform) {

            DB::table('profile_platforms')
                ->where('profile_id', $this->profile_id)
                ->where('platform_id', $platform['value'])
                ->update(
                    [
                        'platform_order' => $platform['order']
                    ]
                );
        }
        // $this->emit('refresh-platforms');
    }

    public function addPlatform($id, $path, $label, $direct, $title, $input)
    {
        $this->platformId = $id;
        $this->path = $path;
        $this->label = $label;
        $this->direct = $direct;
        $this->title = $title;
        $this->input = $input;
        $this->isEditMode = false;
    }

    public function editPlatform($id, $path, $label, $direct, $title, $input)
    {
        $this->platformId = $id;
        $this->path = $path;
        $this->label = $label;
        $this->direct = $direct;
        $this->title = $title;
        $this->input = $input;
        $this->isEditMode = true;
    }

    public function deletePlatform($id)
    {
        $this->platformId = $id;
        $this->c_modal_heading = 'Are You Sure';
        $this->c_modal_body = 'You Want To Delete Link';
        $this->c_modal_btn_text = 'Link Delete';
        $this->c_modal_btn_color = 'btn-danger';
        $this->c_modal_method = 'PlatformDelete';
        $this->dispatchBrowserEvent('confirm-modal');
    }

    public function PlatformDelete()
    {
        $platform = DB::table('profile_platforms')
            ->where('profile_id', $this->profile_id)
            ->where('platform_id', $this->platformId)
            ->first();

        if (!$platform) {
            $this->dispatchBrowserEvent('close-modal');
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',
                'message' => 'Platform not found!',
            ]);
        } else {
            $platform = DB::table('profile_platforms')
                ->where('profile_id', $this->profile_id)
                ->where('platform_id', $this->platformId)
                ->delete();

            $this->dispatchBrowserEvent('close-modal');
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'message' => 'Platform Delete Successfully!',
            ]);
            $this->emit('refresh-platforms');
        }
    }

    public function closeModal()
    {
        $this->c_modal_heading = '';
        $this->c_modal_body = '';
        $this->c_modal_btn_text = '';
        $this->c_modal_btn_color = '';
        $this->c_modal_method = '';
        $this->dispatchBrowserEvent('close-modal');
    }

    private function userPlatform($id, $profileId)
    {
        $userPlatform = DB::table('profile_platforms')
            ->select(
                'platforms.id',
                'profile_platforms.user_id',
                'profile_platforms.path',
                'profile_platforms.label',
                'profile_platforms.direct',
            )
            ->join('platforms', 'platforms.id', 'profile_platforms.platform_id')
            ->where('platform_id', $id)
            ->where('profile_id', $profileId)
            ->first();

        return $userPlatform;
    }

    // Load platform data for editing or adding
    public function savePlatform()
    {
        $platform = Platform::where('id', $this->platformId)
            ->where('status', 1)
            ->first();
        if (!$platform) {
            $this->dispatchBrowserEvent('close-modal');
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',
                'message' => 'Platform not active!',
            ]);
        } else {
            $baseUrl = $platform->baseURL;
            if ($baseUrl) {
                if (substr($baseUrl, -1) == '/') {
                    $baseUrl = substr($baseUrl, 0, -1);
                }
            }

            $user_platform = DB::table('profile_platforms')
                ->where('platform_id', $this->platformId)
                ->where('profile_id', $this->profile_id)
                ->first();
            if ($user_platform) {
                // Update existing platform
                $path = $this->path;

                DB::table('profile_platforms')
                    ->where('platform_id', $this->platformId)
                    ->where('profile_id', $this->profile_id)
                    ->update([
                        'label' => $this->label,
                        'path' => $path,
                        'direct' => $this->direct ? $this->direct : 0
                    ]);
                $this->dispatchBrowserEvent('close-modal');
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'message' => 'Platform Update Successfully!',
                ]);
            } else {
                // Add new platform
                $path = $this->path;
                $latestPlatform = DB::table('profile_platforms')
                    ->where('profile_id', $this->profile_id)
                    ->orderBy('platform_order', 'desc')
                    ->get()
                    ->first();

                DB::table('profile_platforms')->insert([
                    'profile_id' => $this->profile_id,
                    'platform_id' => $this->platformId,
                    'direct' => $this->direct ? $this->direct : 0,
                    'label' => $this->label,
                    'path' => $path,
                    'platform_order' => $latestPlatform ? ($latestPlatform->platform_order + 1) : 1,
                    'created_at' => now()
                ]);
                $this->dispatchBrowserEvent('close-modal');
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'message' => 'Platform add Successfully!',
                ]);
            }
            $this->emit('refresh-platforms');
            $this->emit('refresh-profile', $this->profile_id);
        }
    }

    private function getUserPlatformDetails($id, $profileId)
    {
        $userPlatform = DB::table('profile_platforms')
            ->where('platform_id', $id)
            ->where('profile_id', $profileId)
            ->first();

        if ($userPlatform) {
            return [
                'path' => $userPlatform->path,
                'label' => $userPlatform->label,
                'platform_order' => $userPlatform->platform_order,
                'direct' => $userPlatform->direct
            ];
        }
        return null;
    }

    private function checkPlatformSaved($platformId, $userPlatforms)
    {
        if (in_array($platformId, $userPlatforms)) {
            return 1;
        }
        return 0;
    }

    public function categoryWithPlatforms($searchTerm = null)
    {
        // $categories = Category::whereExists(function ($query) {
        //     $query->select(DB::raw(1))
        //         ->from('platforms')
        //         ->whereRaw('platforms.category_id = categories.id')
        //         ->where('platforms.status', '=', '1');
        // });
        $categories = Category::whereHas('platforms', function ($query) {
            $query->where('status', 1);
        });
        if ($searchTerm) {
            $categories->whereHas('platforms', function ($query) use ($searchTerm) {
                $query->where('title', 'like', "%{$searchTerm}%")
                    ->where('status', 1);
            });
        }
        $categories = $categories->get();

        $userPlatforms = DB::table('profile_platforms')
            ->select(
                'platforms.id as platform_id'
            )
            ->join('platforms', 'platforms.id', 'profile_platforms.platform_id')
            ->where('profile_id', $this->profile_id)
            ->get()
            ->toArray();

        $userPlatforms = array_column($userPlatforms, 'platform_id');

        // Create an empty array to hold the transformed data
        $transformedResponse = [];

        // Loop through each category in the original response
        foreach ($categories as $category) {
            $totalPlatforms = 0;
            // Create a new array to hold the transformed category data
            $transformedCategory = [];

            // Add the desired properties to the transformed category
            $transformedCategory['id'] = $category->id;
            $transformedCategory['name'] = $category->name;
            $transformedCategory['title_en'] = $category->name;
            $transformedCategory['title_sv'] = $category->name_sv;

            // Create an empty array to hold the transformed platforms
            $transformedPlatforms = [];

            $platforms = Platform::where('category_id', $category->id)->where('status', 1);
            if ($searchTerm) {
                $platforms->where(function ($query) use ($searchTerm) {
                    $query->where('platforms.title', 'like', "%{$searchTerm}%");
                });
            }
            $platforms = $platforms->get();

            // Loop through each platform in the category
            foreach ($platforms as $platform) {
                $totalPlatforms = $totalPlatforms + 1;
                // Create a new array to hold the transformed platform data
                $transformedPlatform = [];

                //Get extra details from profile_platforms table
                $userPlatform = $this->getUserPlatformDetails($platform->id, $this->profile_id);

                // Add the desired properties to the transformed platform
                $transformedPlatform['id'] = $platform->id;
                $transformedPlatform['title'] = $platform->title;
                $transformedPlatform['icon'] = $platform->icon;
                $transformedPlatform['input'] = $platform->input;
                $transformedPlatform['baseURL'] = $platform->baseURL;
                $transformedPlatform['placeholder_en'] = $platform->placeholder_en;
                $transformedPlatform['placeholder_sv'] = $platform->placeholder_sv;
                $transformedPlatform['description_en'] = $platform->description_en;
                $transformedPlatform['description_sv'] = $platform->description_sv;
                $transformedPlatform['path'] = $userPlatform ? $userPlatform['path'] : null;
                $transformedPlatform['label'] = $userPlatform ? $userPlatform['label'] : null;
                $transformedPlatform['direct'] = $userPlatform ? $userPlatform['direct'] : 0;
                $transformedPlatform['platform_order'] = $userPlatform ? $userPlatform['platform_order'] : null;
                $transformedPlatform['saved'] = $userPlatforms ? $this->checkPlatformSaved($platform->id, $userPlatforms) : 0;


                // Add the transformed platform to the array of transformed platforms
                $transformedPlatforms[] = $transformedPlatform;
            }

            // Add the array of transformed platforms to the transformed category
            $transformedCategory['totalPlatforms'] = $totalPlatforms;
            $transformedCategory['platforms'] = $transformedPlatforms;


            // Add the transformed category to the array of transformed categories
            $transformedResponse[] = $transformedCategory;
        }
        //sort profile own platforms
        $sort = [];
        foreach ($transformedResponse as $value) {
            foreach ($value['platforms'] as $v) {
                if ($v['saved'] == 1) {
                    array_push($sort, $v);
                }
            }
        }
        array_multisort(array_column($sort, 'platform_order'), SORT_ASC, $sort);
        // return $sort;
        return [
            'transformedResponse' => $transformedResponse,
            'sort' => $sort
        ];
    }

    public function search()
    {
        $data = $this->categoryWithPlatforms($this->searchTerm);
        return $data;
    }
    public function render()
    {
        $platforms = $this->search();
        return view('livewire.enterprise.profile.platforms', [
            'platforms' => $platforms['transformedResponse'],
            'sort_platform' => $platforms['sort']
        ]);
    }
}