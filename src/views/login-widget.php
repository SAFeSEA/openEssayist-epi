<style>
<!--
.form-signin {
    
    border: 1px solid #E5E5E5;
    border-radius: 5px 5px 5px 5px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    margin: 60px auto 20px;
    max-width: 300px;
    padding: 19px 29px 29px;
}

-->
</style>

<div class="row-fluid">
<div class="span6 offset3">
<form class="form-signin" action='/login' method='POST'>
	<h2 class="form-signin-heading">Please sign in</h2>
	<input type="text" placeholder="Username" name="username" id="username" 
		class="input-block-level tt"> <input type="password" name="password" id="password"
		placeholder="Password" class="input-block-level"> <label
		class="checkbox"> <input type="checkbox" value="remember-me"> Remember
		me
	</label>
	<button type="submit" class="btn btn-large btn-primary">Sign in</button>
</form>
</div>
</div>