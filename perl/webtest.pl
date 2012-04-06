#!/usr/bin/perl
use strict;
use warnings;
use HTTP::WebTest;
use HTTP::WebTest::Plugin::ResponseTimeTest;

my $url = $ARGV[0];
my $tests = [
{ test_name    => 'ResponseTest',
    url          => $url,
    min_rtime    => 0,
    max_rtime    => 8,
    plugins      => [ '::ResponseTimeTest']
}
];

my $webtest = new HTTP::WebTest;
$webtest->run_tests($tests);

