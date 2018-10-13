<div class="container">
    <div class="row">    
        <div class="col-xs-8 col-xs-offset-2">
        <form action="?page=user_matches" method="post">
		    <div class="input-group">
                <div class="input-group-btn search-panel">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    	<span id="search_concept">Filtres/tri</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                       <li><p><b>Tri</b></p></li>
                      <div class="radio">
                        <label><input type="radio" name="tri" value="matchesAsc" />Asc</label>
                        <label><input type="radio" name="tri" value="matchesDesc" />Desc | Matches</label>
                      </div>
                      <div class="radio">
                        <label><input type="radio" name="tri" value="tagsAsc" />Asc</label>
                        <label><input type="radio" name="tri" value="tagsDesc" />Desc | Tags</label>
                      </div>
                      <div class="radio disabled">
                        <label><input type="radio" name="tri" value="locationAsc" />Asc</label>
                         <label><input type="radio" name="tri" value="locationDesc" />Desc | Localisation</label>
                      </div>
                      <div class="radio disabled">
                        <label><input type="radio" name="tri" value="ageAsc" />Asc</label>
                        <label><input type="radio" name="tri" value="ageDesc" />Desc | Age</label>
                      </div>
                      <div class="radio disabled">
                        <label><input type="radio" name="tri" value="popularityAsc" />Asc</label>
                        <label><input type="radio" name="tri" value="popularityDesc" />Desc | Popularitée</label>
                      </div>
                      <li class="divider"></li>
                      <li><p><b>Filtres</b></p></li>
                      <label><input type="text" name="filter_age_min" value="0"/ >Age min.</label>            
                      <label style="margin-left: 5px"><input type="text" name="filter_age_max" value="99"/ >Age max.</label><br/>
                      <label><input type="text" name="filter_matches_min" value="0"/ >Matches min.</label>            
                      <label style="margin-left: 5px"><input type="text" name="filter_matches_max" value="99"/ >Matches max.</label><br/>
                      <label><input type="text" name="filter_location_min" value="0"/ >Distance min.</label>	
                      <label style="margin-left: 5px"><input type="text" name="filter_location_max" value="9999999"/ >Distance max.</label><br />
                      <label><input type="text" name="filter_popularity_min" value="0"/ >Popularitée min.</label> 
                      <label style="margin-left: 5px"><input type="text" name="filter_popularity_max" value="9999999"/ >Popularitée max.</label><br />
                      <label><input type="text" name="filter_tags_min" value="0"/ >Tags min. (Communs)</label> 
                      <label style="margin-left: 5px"><input type="text" name="filter_tags_max" value="9999999"/ >Tags max. (Communs)</label>
                    </ul>
                </div>
                <input type="hidden" name="search_param" value="all" id="search_param">         
                <span class="input-group-btn">
                    <input type="submit" class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
                </span>
            </div>
            </form>
            </div>
        </div>
	</div>
