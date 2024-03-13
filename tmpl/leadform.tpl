
	<div id="center_block">
		<div class="no-color">
			<h2 class="text-center"><?=Config::COMPANY_NAME?></h2>
			<form method="post" name="auth" action="/" onsubmit="return checkForm(this)" class="form" role="form" accept-charset="UTF-8" id="auth">
				<div class="form-group">
					<div id="login_div">
						<div class="input-group" >
							<span class="input-group-addon"><i class="glyphicon glyphicon-user" aria-hidden="true"></i></span>
							<input type="text" class="form-control" name="first_name" id="first_name"   placeholder="Enter Name" data-type="text" required/>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div id="login_div">
						<div class="input-group" >
							<span class="input-group-addon"><i class="glyphicon glyphicon-user" aria-hidden="true"></i></span>
							<input type="text" class="form-control" name="last_name" id="last_name"   placeholder="Enter Lastname" data-type="text" required />
						</div>
					</div>
				</div>
				<div class="form-group">
					<div  id="password_div">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock" aria-hidden="true"></i></span>
							<input type="text"
								   class="form-control" name="phone" id="phone"
								   oninput="this.value = this.value.replace(/\D/g, '')"
								   placeholder="Enter Phone" data-type="text" required />
						</div>
					</div>
				</div>
				<div class="form-group">
					<div  id="password_div">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock" aria-hidden="true"></i></span>
							<input type="text" class="form-control" name="email" id="email" placeholder="Enter Email" data-type="text" required />
						</div>
					</div>
				</div>
				<div >
					<input type="submit" class="form-control btn btn-success"  name="auth" value="Send Lead" />
				</div>
			</form>
		</div>
	</div>

