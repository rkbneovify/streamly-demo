//
//  Channel.swift
//  Streamly
//
//  Created by Rohit Singh on 5/12/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation

class Channel: CustomStringConvertible {
  var number: Int
  var name: String
  var programs: [Program]?
  var logo:String
  var url:String

    init(number: Int, name: String,logo:String,url:String) {
    self.number = number
    self.name = name
    self.logo = logo
    self.url  = url
  }

  var description: String {
    return "Channel \(self.number) - \(self.name)"
  }
}
