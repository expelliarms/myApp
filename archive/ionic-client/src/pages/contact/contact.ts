import { Component } from '@angular/core';
import { NavController } from 'ionic-angular';
import { RecsService } from '../../services/rest/recs-service';
import { InfoPage } from '../info/info';

@Component({
  selector: 'page-contact',
  templateUrl: 'contact.html'
})
export class ContactPage {

  constructor(public navCtrl: NavController, private recsService: RecsService) {
  	this.getRecs(13);
  }
  getRecs(user_id){
  	this.recsService.searchRecs(user_id).subscribe(
        data => {
            console.log(data);
            this["offers"] = data.past;
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
