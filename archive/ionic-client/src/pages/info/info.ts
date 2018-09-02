import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
 
@IonicPage()
@Component({
  selector: 'page-info',
  templateUrl: 'info.html',
})
export class InfoPage {
 
    offer: {};  
 
    constructor(public navCtrl: NavController, public navParams: NavParams) {
        this.offer = navParams.get('offer');
    }
 
}