import { Injectable } from '@angular/core';
import {Http} from '@angular/http';
import 'rxjs/add/operator/map';
 
@Injectable()
export class MovieService {
 
    constructor(private http:Http) {
 
    }
 
    searchMovies(movieName) {
        var url = 'http://172.31.98.84/vodafoneu/search1?query=' + encodeURI(movieName);
        console.log(url);
        var response = this.http.get(url).map(res => res.json());
        return response;
    }    
}