#!/usr/bin/ruby
ENV['HOME'] = `/bin/bash -c "echo ~"`
ENV['GEM_HOME'] = ENV['HOME'] + '/.gem/ruby/2.1.5/gems'
ENV['GEM_PATH'] = ENV['HOME'] + '/.gem/ruby/2.1.5'

APP_PATH = ENV['HOME'] + '/calphirho'

require_relative APP_PATH + '/config/environment'

class Rack::PathInfoRewriter
  def initialize(app)
    @app = app
  end

  def call(env)
    env.delete('SCRIPT_NAME')
    parts = env['REQUEST_URI'].split('?')
    env['PATH_INFO'] = parts[0]
    env['QUERY_STRING'] = parts[1].to_s
    @app.call(env)
  end
end

Rack::Handler::FastCGI.run Rack::PathInfoRewriter.new(CalPhiRho::Application)
