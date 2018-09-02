import { Injectable } from '@angular/core';
import {Http} from '@angular/http';
import 'rxjs/add/operator/map';
 
@Injectable()
export class RecsService {
 
    constructor(private http:Http) {
 
    }
 
    searchRecs(user_id) {
        var url = '/vodafoneu/get_recs.py?user_id=45';
        var response = this.http.get(url).map(res => res.json());;
        console.log(response)
        return response;
    }    
}