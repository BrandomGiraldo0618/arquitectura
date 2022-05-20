import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { ApirestService } from '@core/services/apirest.service';
import { SingletonService } from '@core/services/singleton.service';
import { FormGroup, Validators, FormBuilder } from '@angular/forms';
import { Location } from '@angular/common';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnInit {

  	form!: FormGroup;

  	constructor(public route: ActivatedRoute,
                public router: Router,
  				private formBuilder: FormBuilder,
  				public service: ApirestService,
  				public singleton: SingletonService,
  				public location: Location) 
  	{ 
  		this.buildForm(); 
  		this.getDepartments();
  	}

  	ngOnInit(): void {
  	
  	}

  	/**
     *
     * It creates the user form with validations
     *
     */
    
  	private buildForm() 
  	{
  		let validatorsPassword = [Validators.required, Validators.minLength(6), Validators.maxLength(20)];

	    this.form = this.formBuilder.group({
            id: [0],
	      	name: ['', [Validators.required]],
	      	lastname: ['', [Validators.required]],
	      	email: ['', [Validators.required, Validators.email]],
	      	role_id: ['', [Validators.required]],
	      	status: ['', [Validators.required]],
	      	password: ['', validatorsPassword],
	      	confirm_password: ['', validatorsPassword],
	      	city_id: ['', validatorsPassword],
	      	department_id: ['', validatorsPassword]
	    });
    }


    /**
     *
     * Create or update function
     *
     */
    
    save(event: Event)
    {
        event.preventDefault();
        if(this.form.invalid)
        {
            this.form.markAllAsTouched();
            return;
        }

        let values = this.form.value;
        let body = new FormData();
        Object.keys(values).forEach(key => body.append(key, values[key]));

        let url = 'users';
        //-- Open Loading

        this.service.queryPost(url, values).subscribe(
            (response: any) =>
            {      
                //-- Close Loading
                if(response.success)
                {
                    this.singleton.showAlert({type: 'success', content: response.message});
                    this.router.navigate(['/admin/usuarios']);
                }
                else 
                {
                    this.singleton.showAlert({type: 'error', content: response.message});
                }
            },
            (err: any) => {}
        );
    }


    getDepartments(){

    }
    
}
