<?php

namespace App\Http\Controllers\Admin;

use App\DTO\CreateSupportDTO;
use App\DTO\UpdateSupportDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateSupport;
use App\Models\Support;
use App\Services\SupportService;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function  __construct(
        protected SupportService $service
    ) {}

    public function index(Request $request)
    {

        $supports = $this->service->getAll($request->filter);//pegar todos os suportes
        dd($supports);
        return view('admin/supports/index', compact('supports','xss'));
    }

    public function show(string|int $id){

        if(!$support = $this->service->findOne($id)){ 
            return back();
        } 
        // Support::find = recuperar o suporte pelo id, vai na coluna id 
        // Support::where = filtra pela coluna id
        // Support::find($id);
        // Support::where('id', $id)->first();
        // Support::where('id','=' $id)->first();
        
        return view('admin/supports/show', compact('support'));
    }

    public function create(){
        return view('admin/supports/create');
    }

    public function store(StoreUpdateSupport $request, Support $support){ // request = pegar os dados da requisição 
       
        $this->service->new(
            CreateSupportDTO::makeFromRequest($request)
        );

        return redirect()->route('supports.index');
    }

    public function edit(string $id){

       // if(!$support = Support::where('id',$id)->first()){ 
       if(!$support = $this->service->findOne($id)){
           return back();
       }  
         
       return view('admin/supports.edit', compact('support'));

    }

    public function update(StoreUpdateSupport $request, Support $support, string $id){

        $support = $this->service->update( //se encontrar
            UpdateSupportDTO::makeFromRequest($request)
        );

        if(!$support ){
            return back();
        }

        //alternativa:
        // $support->subject = $request->subject;
        // $support->body = $request->body;
        // $support->save();


        return redirect()->route('supports.index');


    }

    public function destroy(string $id)
    {

        $this->service->delete($id);

        return redirect()->route('supports.index');
    }

}

