<?php
/**
 * Un process builder custom pour Youtube-dl PHP pour faire une installation custom de Youtube dl et lui faire pointer les bins sur le path du projet
 * @link https://github.com/norkunas/youtube-dl-php#custom-process-instantiation
 *
 * @package wsl 
 */


use Symfony\Component\Process\Process;
use YoutubeDl\Process\ProcessBuilderInterface;

class Youtube_DL_ProcessBuilder implements ProcessBuilderInterface
{
    public function build(?string $binPath, ?string $pythonPath, array $arguments = []): Process
    {
        $process = new Process([$binPath, $pythonPath, ...$arguments]);
        return $process;
    }
}