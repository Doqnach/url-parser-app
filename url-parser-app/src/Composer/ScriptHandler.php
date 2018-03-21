<?php
    namespace App\Composer;

    use Composer\Installer\PackageEvent;
    use Symfony\Component\Filesystem\Exception\IOException;
    use Symfony\Component\Filesystem\Filesystem;
    use Symfony\Component\Finder\Finder;

    /**
     * composer post update/install hooks
     *
     * @package App\Composer
     */
    class ScriptHandler
    {
        /**
         * Install bootstrap dist into assets
         *
         * @param \Composer\Installer\PackageEvent $event
         */
        public static function installBootstrap(PackageEvent $event) : void
        {
            $package = $event->getOperation()->getReason()->getReasonData();
            if ($package !== 'twbs/bootstrap') {
                return;
            }

            try {
                $filesystem = new Filesystem();
                $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');

                try {
                    // css
                    $source = sprintf('%s/twbs/bootstrap/dist/css', $vendorDir);
                    $target = sprintf('%s/../public/assets/css/bootstrap', $vendorDir);
                    $filesystem->mirror($source, $target, null, ['override' => true, 'delete' => true]);

                    // js
                    $source = sprintf('%s/twbs/bootstrap/dist/js', $vendorDir);
                    $target = sprintf('%s/../public/assets/js/bootstrap', $vendorDir);
                    $filesystem->mirror($source, $target, null, ['override' => true, 'delete' => true]);

                    $event->getIO()->write('Linked Bootstrap assets.');
                } catch (IOException $e) {
                    $event->getIO()->writeError('Failed linking Bootstrap assets: ' . $e->getMessage());
                }
            } catch (\RuntimeException $e) {
                $event->getIO()->writeError('Failed to get vendor-dir: ' . $e->getMessage());
            }
        }

        /**
         * Install jQuery component into assets
         *
         * @param \Composer\Installer\PackageEvent $event
         */
        public static function installJQuery(PackageEvent $event) : void
        {
            $package = $event->getOperation()->getReason()->getReasonData();
            if ($package !== 'components/jquery') {
                return;
            }

            try {
                $finder = new Finder();
                $filesystem = new Filesystem();

                $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');

                try {
                    // js
                    $source = sprintf('%s/components/jquery', $vendorDir);
                    $target = sprintf('%s/../public/assets/js/jquery', $vendorDir);
                    $iter = $finder->in($source)->name('/\.(?:js|map)$/');
                    $filesystem->mirror($source, $target, $iter, ['override' => true, 'delete' => true]);

                    $event->getIO()->write('Linked jQuery assets.');
                } catch (IOException $e) {
                    $event->getIO()->writeError('Failed linking jQuery assets: ' . $e->getMessage());
                }
            } catch (\RuntimeException $e) {
                $event->getIO()->writeError('Failed to get vendor-dir: ' . $e->getMessage());
            }
        }
    }
