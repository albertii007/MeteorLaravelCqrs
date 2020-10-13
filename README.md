This package is for supporting CQRS Pattern on Laravel.

install package composer require meteorcqrs/meteorlaravel

Step 1.

Register Package on config/app , go to end of array of 'providers' and add this line of code.
MeteorCqrs\Meteor\MeteorServiceProvider::class


Step 2.

on Controller.php add code.

protected $_meteor;

public function __construct(IMeteor $meteor)
{
  $this->_meteor = $meteor;
}


Step 3.

on YourFileControoller add this on everyFunction.

public function getUserById(GetUserByIdHandler $query, Request $request)
{
  return $this->_meteor->Send($query,$request);
}

on class GetUserByIdHandler.php that can be anywhere on your project.

class GetUserByIdHandler implements IMeteorHandler
{
  public function Handler(Request $request)
  {
    return 'okay';
  }
}

Note if you send requests to _meteor->Send an file with Name Validator will be created automatically and that file will be executed before createing any other file.
