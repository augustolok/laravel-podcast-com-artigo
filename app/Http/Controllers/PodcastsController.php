<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use App\PodcastItem;
use App\Podcast;
use App\artigo;
use Auth;
use Feeds;
use Image;

class PodcastsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function show($id) {

        $user = Auth::user();

        $podcast_items = DB::table('podcast_items')
            ->where('user_id', '=',1)
            ->where('is_mark_as_read', '!=', 1)
            ->orderBy('published_at', 'desc')->paginate(15);

        $podcasts = DB::table('podcasts')
            ->where('user_id', '=',1)
            ->get();

        $data = array(
            'podcasts'          => $podcasts,
            'podcast_items'     => $podcast_items,
            'user'              => $user,
        );

        return view('podcasts.list', $data);

    }

    public function index() {

        $user = Auth::user();

        $podcast_items = DB::table('podcast_items')
            ->where('user_id', '=', 1)
            ->where('is_mark_as_read', '!=', 1)
            ->orderBy('published_at', 'desc')->paginate(15);

        $podcasts = DB::table('podcasts')
            ->where('user_id', '=', 1)
            ->get();

        $data = array(
            'podcasts'          => $podcasts,
            'podcast_items'     => $podcast_items,
            'user'              => $user,
        );

        return view('podcasts.list', $data);

    }
    public function store_artigo(){
        
    }
    public function artigo(){
        if(!empty($_POST)){
          
           $artigo = New artigo;
           artigo::create([
               'titulo'=>$_POST['titulo'],
               'url'=>$_POST['url'],
               'mensagem'=>$_POST['texto'],
           ]);
           $artigoItems = DB::table('artigos')
           ->orderBy('id', 'DESC')->paginate(15);
           $data = array(
               'artigoItems' => $artigoItems,
           );
           return view('podcasts.artigo',compact('artigoItems'));
           
        }else{
            $artigoItems = DB::table('artigos')
            ->orderBy('id', 'DESC')->paginate(15);
            $data = array(
                'artigoItems' => $artigoItems,
            );
            return view('podcasts.artigo',compact('artigoItems'));
        }
        //return view('podcasts.artigo');
    }
    public function artigofull($id){
       
            $artigoItems = DB::table('artigos')
            ->where('id','=',$id)
            ->orderBy('id', 'DESC')->paginate(15);
            $data = array(
                'artigoItems' => $artigoItems,
            );
            return view('podcasts.artigofull',compact('artigoItems'));
        
        //return view('podcasts.artigo');
    }

    /**
     * Return a view to manage podcasts
     * @return view
     */
    public function manage() {
        $user = Auth::user();
        if(!empty($user)){

        $podcasts = DB::table('podcasts')
            ->where('user_id', '=', 1)
            ->get();

        $data = array(
            'podcasts'          => $podcasts,
            'user'              => $user,
        );

        return view('podcasts.manage', $data);
    }
    else{
        return redirect('login');
    }

    }

    /**
     * Return the list of favorites for a user to a view
     * @return [type] [description]
     */
    public function favorites() {
        
        $podcastItems = DB::table('podcast_items')
            ->where('user_id', '=', Auth::user()->id)
            ->where('is_mark_as_favorite', '!=', 0)
            ->orderBy('published_at', 'desc')->paginate(15);

        $data = array(
            'podcastItems' => $podcastItems,
        );

        return view('podcasts.favorites', $data);
    }

    /**
     * Return a view to manage settings
     * @return view
     */
    public function settings() {
        return view('podcasts.settings');
    }

    /**
     * Store a newly created podcast in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // create "images" directory under "public" directory if it doesn't exist
        // if (!File::exists(public_path() . '/images')) {
        //     File::makeDirectory(public_path() . '/images');
        // }
        
        $user = Auth::user();
        if(!empty($user)){
        if ($request->feed_url) {

            $feed = Feeds::make($request->feed_url);
            $feed->force_feed(true);
            $feed->handle_content_type();
            $podcastName = $feed->get_title();

            if ($podcastName && $podcastName != '') {
                // check if the feed's first item has an audio file in its enclosure
                if (strpos($feed->get_items()[0]->get_enclosure()->get_type(), 'audio') !== false) {
                    $podcastMachineName = strtolower(preg_replace('/\s+/', '', $podcastName));

                    // Save the feed thumbnail to file system and save file path to database
                    $img = Image::make($feed->get_image_url())->resize(100, 100);
                    $img->save(public_path('images/' . $podcastMachineName . '.png'));

                    Podcast::create([
                        'name' => $podcastName ? $podcastName : '',
                        'machine_name' => $podcastMachineName,
                        'feed_url' => $request->feed_url,
                        'feed_thumbnail_location' => 'images/' . $podcastMachineName . '.png',
                        'user_id' => $user->id,
                        'web_url' => $feed->get_link(),
                    ]);

                    foreach ($feed->get_items() as $item) {
                        $group = $item->get_item_tags('http://www.itunes.com/dtds/podcast-1.0.dtd', 'image');
                        PodcastItem::create([
                          
                            'podcast_id' => DB::table('podcasts')
                                ->select('id', 'machine_name')
                                ->where('machine_name', '=', $podcastMachineName)->first()->id,
                            'user_id' => $user->id,
                            'url' => $item->get_permalink(),
                            'audio_url' => $item->get_enclosure()->get_link(),
                            'title' => $item->get_title(),
                            'description' => trim(strip_tags(str_limit($item->get_description(), 200))),
                            'published_at' => $item->get_date('Y-m-d H:i:s'),
                            'imge' =>$group[0]['attribs']['']["href"],
                        ]);
                    }

                    // @todo Podcast was added
                    return redirect('podcasts/player');
                } else {
                    // @todo flash msg
                    return 'This doesn\'t seem to be an RSS feed with audio files. Please try another feed.';
                }
            } else {
                // @todo Could not add podcast
                return 'Sorry, this feed cannot be imported. Please try another feed';
            }

        } else {
            // @todo use validation
            return 'Invalid feed URL given.';
        }
    }
    else{
        return redirect('login');
    }
    }

    /*
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
     public function artigocompleto(Request $request, $id){
        $artigoItems = DB::table('artigos')
        ->where('id','=',$id)
        ->orderBy('id', 'DESC')
        ->get();

     }

    public function atiualziar()
    {
       

        $uniquePodcasts = DB::table('podcasts')
            ->select('id', 'feed_url', 'machine_name')
            ->groupBy('id')->get();

        foreach ($uniquePodcasts as $podcast) {
            $usersSubscribedToThisPodcast = DB::table('podcasts')
                ->select('user_id', 'id as podcast_id')
                ->where('machine_name', '=', $podcast->machine_name)
                ->get();

            $items = Feeds::make($podcast->feed_url)->get_items();

            // Calculate 48 hours ago
            $yesterday = time() - (24 * 2 * 60 * 60);

            foreach ($items as $item) {
                $itemPubDate = $item->get_date();

                

                    // new items
                    foreach ($usersSubscribedToThisPodcast as $subscriber) {

                        $podcastItemsCount = DB::table('podcast_items')
                            ->select('user_id', 'title', 'podcast_id')
                            ->where('title', '=', strip_tags($item->get_title()))
                            ->where('user_id', '=', $subscriber->user_id)
                            ->where('podcast_id', '=', $subscriber->podcast_id)
                            ->count();

                        // if this item is not already in the DB
                        if ($podcastItemsCount == 0) {
                            $group = $item->get_item_tags('http://www.itunes.com/dtds/podcast-1.0.dtd', 'image');
                            PodcastItem::create([
                                'user_id' => $subscriber->user_id,
                                'title' => strip_tags($item->get_title()),
                                'description' => strip_tags(str_limit($item->get_description(), 100)),
                                'published_at' => $item->get_date('Y-m-d'),
                                'url' => $item->get_permalink(),
                                'audio_url' => $item->get_enclosure()->get_link(),
                                'podcast_id' => $subscriber->podcast_id,
                                'imge' =>$group[0]['attribs']['']["href"],
                                
                            ]);
                        }
                    }
                
            }

        }
    }
    /**
     * Delete a podcast
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Podcast::findOrFail($id)->delete();

        return back()->with('success', 'Successfully deleted the Podcast!');

    }

}
