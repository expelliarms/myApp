import { Component } from '@angular/core';
import { NavController } from 'ionic-angular';
import { MovieService } from '../../services/rest/movie-service';
import { InfoPage } from '../info/info';
import { SpeechRecognition } from '@ionic-native/speech-recognition';

@Component({
  selector: 'page-home',
  templateUrl: 'home.html'
})
export class HomePage {

  
  constructor(public navCtrl: NavController, private movieService: MovieService, private speechRecognition: SpeechRecognition) {

  }

  /*
    ngOnInit() {

	    this.speechRecognition.hasPermission()
	      .then((hasPermission: boolean) => {

	        if (!hasPermission) {
	        this.speechRecognition.requestPermission()
	          .then(
	            () => console.log('Granted'),
	            () => console.log('Denied')
	          )
	        }

	     });

	  }*/

  searchForMovie(event, key) {
        if(event.target.value.length > 2) {
            this.movieService.searchMovies(event.target.value).subscribe(
                data => {
                    console.log(data);
                    this["movies"] = data; 
                },
                err => {
                    console.log(err);
                },
                () => console.log('Movie Search Complete')
            );
        }
    }  
     
    selectMovie(event, movie) {
        console.log(movie);  
        this.navCtrl.push(InfoPage, {
            movie: movie
        });
    }  


}
