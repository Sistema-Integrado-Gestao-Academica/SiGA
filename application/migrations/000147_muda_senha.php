    <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/secretary/constants/EnrollmentConstants.php");

class Migration_muda_senha extends CI_Migration {

    public function up() {
        $this->load->model("auth/usuarios_model");

        $users = $this->usuarios_model->getAllUsers();

        foreach ($users as $user) {
            $id = $user['id'];
            $newPassword = $this->usuarios_model->encryptPassword('root');
            $this->usuarios_model->updatePassword($id, $newPassword, 0);
        }
    }

    public function down(){
    }

}