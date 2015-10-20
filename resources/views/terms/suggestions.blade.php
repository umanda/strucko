<hr>
<h2>Your contribution</h2>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title">
                <a class="btn-block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Suggest definition
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
                <form action="{{ action('DefinitionsController@store') }}" method="POST">
                    @include('definitions.form')
                    <button type="submit" class="btn btn-primary">Suggest definition</button>
                </form>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingTwo">
            <h4 class="panel-title">
                <a class="btn-block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Suggest translation
                </a>
            </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
            <div class="panel-body">
                <form method="POST" action="{{ action('ConceptsController@addTranslation', [$term->slug]) }}">
                    @include('terms.translations.form')
                    <button type="submit" class="btn btn-primary">Suggest translation</button>
                </form>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingThree">
            <h4 class="panel-title">
                <a class="btn-block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Suggest synonym
                </a>
            </h4>
        </div>
        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
            <div class="panel-body">
                <form method="POST" action="{{ action('ConceptsController@addSynonym', [$term->slug]) }}">
                    @include('terms.synonyms.form')
                    <button type="submit" class="btn btn-primary">Suggest synonym</button>
                </form>
            </div>
        </div>
    </div>
</div>