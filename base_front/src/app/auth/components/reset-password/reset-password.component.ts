import { Component, OnInit } from '@angular/core';
import { FormGroup, Validators, FormBuilder } from '@angular/forms';
import { ApirestService } from '@core/services/apirest.service';
import { SingletonService } from '@core/services/singleton.service';
import { Router, ActivatedRoute } from '@angular/router';


@Component({
  selector: 'app-reset-password',
  templateUrl: './reset-password.component.html',
  styleUrls: ['./reset-password.component.scss']
})
export class ResetPasswordComponent implements OnInit {

  	form: FormGroup;
  	token: string;

  	constructor(private formBuilder: FormBuilder,
                private service: ApirestService,
                public singleton: SingletonService,
                private router: Router,
                public route: ActivatedRoute) 
  	{
  		this.route.params.subscribe(params => {
            this.token = params['token']; 
        });

  		this.buildForm(); 
  	}

  	ngOnInit(): void 
  	{
  	}

    /**
     *
     * It creates the login form with validations
     *
     */
    
  	private buildForm() 
  	{
	    this.form = this.formBuilder.group({
	      	password: ['', [Validators.required, Validators.minLength(6), Validators.maxLength(20)]],
	      	confirm_password: ['', [Validators.required, Validators.minLength(6), Validators.maxLength(20)]],
	      	token: [this.token, [Validators.required]]
	    });
    }

    /**
     *
     * Recover password function
     *
     */
    
    resetPassword(event: Event)
    {
        event.preventDefault();
        if (this.form.valid) 
        {
            const body = this.form.value;
            
            this.service.queryPostRegular('change-password', body).subscribe(
                (response: any) =>
                {      
                    if(response.success)
                    {
                    	this.singleton.showAlert({type: 'success', content: response.success});
                        this.router.navigate(['/auth/login']);
                    }
                },
                (err: any) => {}
            );
        }
    }
}
