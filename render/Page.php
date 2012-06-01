<?php
	require_once('Template.php');
	require_once('Header.php');
	require_once('Footer.php');
	require_once('Session.php');
	setSession(0,"/");

    class Page extends Config{
		protected $header;
		protected $footer;

		private $page_title;
		private $body_id;

		public $self;

        public function __construct($page_title, $body_id)
		{
			parent::__construct( DIRECTORY_SEPARATOR, 'afterthought.conf' );

			$this->page_title = $page_title;
			$this->body_id = $body_id;
        }

		public function run()
		{
			$this->header = new Header( $this->root );
			$this->footer = new Footer();

			$this->header->run();
			$this->footer->run();

			$this->self = $this->header->self;
        }

        public function build($appContent)
		{
            $tmpl = new Template();
			$tmpl->headerContent = $this->header->generate();
            $tmpl->appContent = $appContent;
			$tmpl->footerContent = $this->footer->generate();
			$tmpl->title = $this->page_title;
			$tmpl->id = $this->body_id;

            return $tmpl->build('page.html');
        }
    }

	function secure($role = 3)
	{
		if( !$_SESSION['active'] )
		{
			header('Location: index.php?code=2');
		}
		else
		{
			if( $_SESSION['roleid'] > $role )
			{
				header('Location: logout.php?fwd=' . urlencode('index.php?code=3'));
			}
		}
	}
?>
