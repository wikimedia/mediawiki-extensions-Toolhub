--[[
-- This program is free software; you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation; either version 2 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License along
-- with this program; if not, write to the Free Software Foundation, Inc.,
-- 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
--]]

local toolhub = {}
local php

local util = require 'libraryUtil'
local checkType = util.checkType

function toolhub.setupInterface( option )
    toolhub.setupInterface = nil
    php = mw_interface
    mw_interface = nil

    mw = mw or {}
    mw.ext = mw.ext or {}
    mw.ext.toolhub = toolhub

    package.loaded[ 'mw.ext.toolhub' ] = toolhub
end

function toolhub.getTool( name )
    checkType( 'getTool', 1, name, 'string' )
    return php.getTool( name )
end

--- Get info for a specific list.
--
-- @param id List id
-- @return Table of list data
function toolhub.getList( id )
    checkType( 'getList', 1, id, 'number' )
    return php.getList( id )
end

return toolhub
