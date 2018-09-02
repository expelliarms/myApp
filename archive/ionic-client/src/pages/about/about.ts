import { Component } from '@angular/core';
import { NavController } from 'ionic-angular';
import { RecsService } from '../../services/rest/recs-service';
import { InfoPage } from '../info/info';


@Component({
  selector: 'page-about',
  templateUrl: 'about.html'
})
export class AboutPage {

  
  constructor(public navCtrl: NavController, private recsService: RecsService) {

  }
  getRecs(event, user_id){
  	this.recsService.searchRecs(user_id).subscribe(
        data => {
            console.log(data);
            this["offers"] = data.future;
        },
        err => {
			alert(err["past"]);            
            console.log(err);
        },
        () => console.log('Movie Search Complete')
    );
  }
  selectOffer(event, offer) {
        console.log(offer);  
        this.navCtrl.push(InfoPage, {
            offer: offer
        });
    }

}
