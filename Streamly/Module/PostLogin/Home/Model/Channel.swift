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

  init(number: Int, name: String) {
    self.number = number
    self.name = name
  }

  var description: String {
    return "Channel \(self.number) - \(self.name)"
  }
}
