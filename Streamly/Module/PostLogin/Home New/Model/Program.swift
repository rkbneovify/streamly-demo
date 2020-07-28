//
//  Program.swift
//  Streamly
//
//  Created by Rohit Singh on 5/12/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//


import Foundation

struct Schedule {
  var start: TimeInterval = 0
  var end: TimeInterval = 0
}

class Program: CustomStringConvertible {

  var title: String
  var schedule: Schedule
  var startDate: Date {
    return Date(timeIntervalSince1970: TimeInterval(self.schedule.start))
  }
  var endDate: Date {
    return Date(timeIntervalSince1970: TimeInterval(self.schedule.end))
  }
  var duration: TimeInterval {
    let duration = self.endDate.addingTimeInterval(-self.schedule.start).timeIntervalSince1970
    return duration
  }

  init(title: String, schedule: Schedule) {
    self.title = title
    self.schedule = schedule
  }

  var description: String {
    return "\(self.title) - \(self.startDate) - \(self.endDate)"
  }
}
