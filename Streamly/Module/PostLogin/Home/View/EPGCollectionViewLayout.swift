//
//  EPGCollectionViewLayout.swift
//  EPG
//
//  Created by Jerald Abille on 3/22/18.
//  Copyright © 2018 Jerald Abille. All rights reserved.
//

import UIKit

class EPGCollectionViewLayout: UICollectionViewLayout {
 let cellWidth = 395.0
    let cellHeight = 120.0
  var cellAttributesDictionary = Dictionary<IndexPath, UICollectionViewLayoutAttributes>()
  var contentSize = CGSize.zero
  var dataSourceDidUpdate = true

  var channels: [ChannelDetails]?
  var programs: [ProgramArray]?

  override var collectionViewContentSize: CGSize {
    return self.contentSize
  }

  override func prepare() {
    
    if !dataSourceDidUpdate {
      let xOffset = collectionView!.contentOffset.x
      let yOffset = collectionView!.contentOffset.y

      if let sectionCount = collectionView?.numberOfSections, sectionCount > 0 {
        for section in 0...sectionCount - 1 {
          if let rowCount = collectionView?.numberOfItems(inSection: section), rowCount > 0 {
            // The first row.
            if section == 0 {
              for item in 0...rowCount - 1 {
                let indexPath = IndexPath(item: item, section: section)
                if let attributes = cellAttributesDictionary[indexPath] {
                  var frame = attributes.frame
                  if item == 0 {
                    frame.origin.x = xOffset + 40.0
                  }
                    frame.origin.y = yOffset + 23.0
                  attributes.frame = frame

                }
              }
            } else {
              let indexPath = IndexPath(item: 0, section: section)
              if let attributes = cellAttributesDictionary[indexPath] {
                var frame = attributes.frame
                frame.origin.x = xOffset + 40.0
                attributes.frame = frame
              }
            }
          }
        }
      }
      return
    }

    dataSourceDidUpdate = false

    if let sectionCount = collectionView?.numberOfSections, sectionCount > 0 {
      for section in 0...sectionCount - 1 {
        var channel: ChannelDetails?
        if section > 0 {
            if let ch = self.channels?[section - 1] {
            channel = ch
          }
        }
        if let rowCount = collectionView?.numberOfItems(inSection: section), rowCount > 0 {
          for item in 0...rowCount - 1 {
            var program: ProgramArray?
            if section > 0 && item > 0 {
              if let _ = channel {
                if let prog = channel!.programs?[item - 1] {
                  program = prog
                }
              }
            }

            let cellIndex = IndexPath(item: item, section: section)
            var xPos = Double(item) * cellWidth
            let yPos = Double(section) * cellHeight

            let cellAttributes = UICollectionViewLayoutAttributes(forCellWith: cellIndex)
            cellAttributes.frame = CGRect(x: xPos, y: yPos, width: cellWidth - 9.0, height: cellHeight)
            let insetFrame = cellAttributes.frame.insetBy(dx: 0.0, dy: 9.0)
            cellAttributes.frame = insetFrame

            if let _ = program {
                xPos = (cellWidth * ((getDateFromTimeStamp(program?.start ?? 0) ?? 0) / 60 / 60) + cellWidth)
                let width = ((duration(program?.start ?? 0 ,program?.end ?? 0) / 60 / 60) * cellWidth)
                cellAttributes.frame = CGRect(x: xPos, y: yPos, width: width - 9.0, height: cellHeight)
                let insetFrame = cellAttributes.frame.insetBy(dx: 0.0, dy: 9.0)
                cellAttributes.frame = insetFrame
            }

            if section == 0 && item == 0 {
              cellAttributes.zIndex = 4
            } else if section == 0 {
              cellAttributes.zIndex = 3
            } else if item == 0 {
              cellAttributes.zIndex = 2
            } else {
              cellAttributes.zIndex = 1
            }

            cellAttributesDictionary[cellIndex] = cellAttributes
          }
        }
      }
    }

    let contentWidth = Double(collectionView!.numberOfItems(inSection: 0)) * cellWidth
    let contentHeight = Double(collectionView!.numberOfSections) * cellHeight
    self.contentSize = CGSize(width: contentWidth, height: contentHeight + 30)
  }

  override func layoutAttributesForElements(in rect: CGRect) -> [UICollectionViewLayoutAttributes]? {
    var attributesInRect = [UICollectionViewLayoutAttributes]()
    for cellAttributes in cellAttributesDictionary.values {
      if rect.intersects(cellAttributes.frame) {
        attributesInRect.append(cellAttributes)
      }
    }

    return attributesInRect
  }

  override func layoutAttributesForItem(at indexPath: IndexPath) -> UICollectionViewLayoutAttributes? {
    return cellAttributesDictionary[indexPath]!

    }

  override func shouldInvalidateLayout(forBoundsChange newBounds: CGRect) -> Bool {
    return true
  }

      func getDateFromTimeStamp(_ timeStamp : Double) -> TimeInterval? {

          let date = NSDate(timeIntervalSince1970: timeStamp)

          let dayTimePeriodFormatter = DateFormatter()
          dayTimePeriodFormatter.dateFormat = "HH:mm"
          dayTimePeriodFormatter.locale = Locale(identifier: "en_US_POSIX")
          dayTimePeriodFormatter.timeZone = TimeZone(abbreviation: "UTC")
       // UnComment below to get only time
      //  dayTimePeriodFormatter.dateFormat = "hh:mm a"

          let dateString = dayTimePeriodFormatter.string(from: date as Date)

          let fullNameArr = dateString.components(separatedBy: ":")
          guard let hour = Int(fullNameArr[0]) else {return nil}
          guard let min = Int(fullNameArr[1]) else {return nil}


          return TimeInterval((hour * 60 * 60) + (min * 60))
      }

    func duration(_ start:TimeInterval,_ end:TimeInterval) -> TimeInterval
    {
        return Date(timeIntervalSince1970: TimeInterval(getDateFromTimeStamp(end) ?? 0)).addingTimeInterval(-(getDateFromTimeStamp(start ) ?? 0)).timeIntervalSince1970


    }

   
}
